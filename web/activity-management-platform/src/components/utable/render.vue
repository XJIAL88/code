<template>

</template>
<script>
    export default {
        name: "render",
        props: {
            renderDom: {
                type: Function,
                default: (h) => h('')
            }
        },
        render(h) {
            return this.renderDom(h)
        },
    }
</script>
