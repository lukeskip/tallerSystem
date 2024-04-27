<template>
    <div class="bg-white loader w-full flex justify-center items-center absolute top-0 right-0"><div class="spinner"></div></div>
</template>
<script setup>

</script>
<style>
.loader{
   z-index: 10;
   min-height: 50vh;
   height: 100%;
}
.h-half-screen {
  height: 50vh;
}
.spinner {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: 9px solid #9e915f;
    animation: spinner-bulqg1 0.6400000000000001s infinite linear alternate,
         spinner-oaa3wk 1.2800000000000002s infinite linear;
 }
 
 @keyframes spinner-bulqg1 {
    0% {
       clip-path: polygon(50% 50%, 0 0, 50% 0%, 50% 0%, 50% 0%, 50% 0%, 50% 0%);
    }
 
    12.5% {
       clip-path: polygon(50% 50%, 0 0, 50% 0%, 100% 0%, 100% 0%, 100% 0%, 100% 0%);
    }
 
    25% {
       clip-path: polygon(50% 50%, 0 0, 50% 0%, 100% 0%, 100% 100%, 100% 100%, 100% 100%);
    }
 
    50% {
       clip-path: polygon(50% 50%, 0 0, 50% 0%, 100% 0%, 100% 100%, 50% 100%, 0% 100%);
    }
 
    62.5% {
       clip-path: polygon(50% 50%, 100% 0, 100% 0%, 100% 0%, 100% 100%, 50% 100%, 0% 100%);
    }
 
    75% {
       clip-path: polygon(50% 50%, 100% 100%, 100% 100%, 100% 100%, 100% 100%, 50% 100%, 0% 100%);
    }
 
    100% {
       clip-path: polygon(50% 50%, 50% 100%, 50% 100%, 50% 100%, 50% 100%, 50% 100%, 0% 100%);
    }
 }
 
 @keyframes spinner-oaa3wk {
    0% {
       transform: scaleY(1) rotate(0deg);
    }
 
    49.99% {
       transform: scaleY(1) rotate(135deg);
    }
 
    50% {
       transform: scaleY(-1) rotate(0deg);
    }
 
    100% {
       transform: scaleY(-1) rotate(-135deg);
    }
 }
</style>