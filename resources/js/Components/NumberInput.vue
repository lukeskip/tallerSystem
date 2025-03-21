<script setup>
import { onMounted, ref, computed, nextTick } from "vue";

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

    // Convertir a número y limitar a 2 decimales
    const number =
        typeof value === "string"
            ? parseFloat(value.replace(/[^\d.]/g, ""))
            : value;

    if (isNaN(number)) return "";

    // Determinar cuántos decimales mostrar basado en el valor de entrada
    const parts = value.toString().split(".");
    const decimals = parts.length > 1 ? parts[1].length : 0;

    // Formatear con Intl.NumberFormat
    return new Intl.NumberFormat("es-MX", {
        minimumFractionDigits: Math.min(decimals, 2),
        maximumFractionDigits: 2,
    }).format(number);
};

const getCursorPosition = (value, selectionStart, oldValue) => {
    const newValue = value.replace(/[^\d.]/g, "");
    const oldNumber = oldValue.replace(/[^\d.]/g, "");

    if (newValue.length > oldNumber.length) {
        // Si estamos agregando números
        return selectionStart + 1;
    } else {
        // Si estamos borrando números
        return selectionStart;
    }
};

const handleInput = (event) => {
    const cursorPosition = event.target.selectionStart;
    const oldValue = displayValue.value;

    // Obtener solo los números y el punto decimal
    let numericValue = event.target.value.replace(/[^\d.]/g, "");

    // Validar que solo haya un punto decimal
    const parts = numericValue.split(".");
    if (parts.length > 2) {
        numericValue = parts[0] + "." + parts.slice(1).join("");
    }

    // Limitar a dos decimales si hay decimales
    if (parts.length === 2 && parts[1].length > 2) {
        numericValue = parts[0] + "." + parts[1].slice(0, 2);
    }

    if (numericValue === "") {
        model.value = 0;
        displayValue.value = "";
        return;
    }

    // Si termina en punto, mantenerlo
    const endsWithDot = numericValue.endsWith(".");

    const number = parseFloat(numericValue);
    if (!isNaN(number)) {
        model.value = number;
        // Si termina en punto, agregarlo después del formateo
        displayValue.value =
            formatMoney(numericValue) + (endsWithDot ? "." : "");

        // Calcular y restaurar la posición del cursor
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

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
    // Formatear el valor inicial
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
