<template>
    <div class="table-title" :class="{border}">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "table-title",
        props:{
            border:{
                type:Boolean,
                default:true,
            },
        }
    }
</script>

<style scoped lang="scss">
    .table-title {
        flex: none;
        height: 50px;
        line-height: 50px;
        font-size: 16px;
        color: #333;
        background: #fff;
        &.border {
            border-bottom: 1px solid #eee;
        }
    }
</style>