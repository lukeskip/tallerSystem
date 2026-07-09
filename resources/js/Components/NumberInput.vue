<script setup>
import { onMounted, ref, computed, nextTick, watch } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "number",
    },
    step: {
        type: String,
        default: "1",
    },
    min: {
        type: String,
        default: undefined,
    },
});

const model = defineModel({
    type: [Number, String],
    required: true,
});

const input = ref(null);
const displayValue = ref("");

const formatMoney = (value) => {
    if (!value && value !== 0) return "";

    // Convert to number and limit to 2 decimals
    const number =
        typeof value === "string"
            ? parseFloat(value.replace(/[^\d.]/g, ""))
            : value;

    if (isNaN(number)) return "";

    // Determine how many decimals to show based on the input value
    const parts = value.toString().split(".");
    const decimals = parts.length > 1 ? parts[1].length : 0;

    // Format with Intl.NumberFormat
    return new Intl.NumberFormat("es-MX", {
        minimumFractionDigits: Math.min(decimals, 2),
        maximumFractionDigits: 2,
    }).format(number);
};

const getCursorPosition = (value, selectionStart, oldValue) => {
    const newValue = value.replace(/[^\d.]/g, "");
    const oldNumber = oldValue.replace(/[^\d.]/g, "");

    if (newValue.length > oldNumber.length) {
        // If we are adding numbers
        return selectionStart + 1;
    } else {
        // If we are deleting numbers
        return selectionStart;
    }
};

const handleInput = (event) => {
    const cursorPosition = event.target.selectionStart;
    const oldValue = displayValue.value;

    // Get only numbers and decimal point
    let numericValue = event.target.value.replace(/[^\d.]/g, "");

    // Validate that there is only one decimal point
    const parts = numericValue.split(".");
    if (parts.length > 2) {
        numericValue = parts[0] + "." + parts.slice(1).join("");
    }

    // Limit to two decimals if there are decimals
    if (parts.length === 2 && parts[1].length > 2) {
        numericValue = parts[0] + "." + parts[1].slice(0, 2);
    }

    if (numericValue === "") {
        model.value = 0;
        displayValue.value = "";
        return;
    }

    // If it ends with a dot, keep it
    const endsWithDot = numericValue.endsWith(".");

    const number = parseFloat(numericValue);
    if (!isNaN(number)) {
        model.value = number;
        // If it ends with a dot, add it after formatting
        displayValue.value =
            formatMoney(numericValue) + (endsWithDot ? "." : "");

        // Calculate and restore cursor position
        nextTick(() => {
            const newPosition = getCursorPosition(
                displayValue.value,
                cursorPosition,
                oldValue
            );
            event.target.setSelectionRange(newPosition, newPosition);
        });
    }
};

watch(() => model.value, (newVal) => {
    let currentNumeric = parseFloat(displayValue.value.replace(/[^\d.]/g, ""));
    if (isNaN(currentNumeric)) currentNumeric = 0;
    
    let newNumeric = parseFloat(newVal);
    if (isNaN(newNumeric)) newNumeric = 0;

    // Only update the view if the numeric value differs,
    // which means the change came from an external assignment.
    if (newNumeric !== currentNumeric) {
        displayValue.value = formatMoney(newVal);
    }
});

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
    // Format the initial value
    displayValue.value = formatMoney(model.value);
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="displayValue"
        ref="input"
        :type="type === 'money' ? 'text' : 'number'"
        :step="step"
        :min="min"
        @input="handleInput"
    />
</template>
