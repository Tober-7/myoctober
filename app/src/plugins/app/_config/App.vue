<template>
    <div class="flex flex-col h-full">
        <div class="px-16 py-8 bg-[#070707] border-b border-neutral-300">
            <p class="text-3xl tracking-widest text-neutral-300 cursor-default">Student Arrivals</p>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {}
</script>

<style>

html, body, #app {
    height: 100%;

    margin: 0;

    background-color: #0A0A0A;
}

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-text-fill-color: rgb(212 212 212 / 1) !important;
    -webkit-box-shadow: 0 0 0 30px #070707 inset !important;
}

</style>