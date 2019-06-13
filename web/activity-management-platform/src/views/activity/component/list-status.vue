<template>
    <div class="list-status">
        <span :style="{background:color}">{{desc}}</span>
    </div>
</template>

<script>
export default {
    name: "listStatus",
    props: {
        color: {
            type: String,
            default: ""
        },
        desc: {
            type: String,
            default: ""
        }
    }
};
</script>

<style lang="scss" scoped>
.list-status {
    span {
        display: inline-block;
        border-radius: 4px;
        width: 70px;
        text-align: center;
        color: #fff;
        padding: 3px 0;
    }
}
</style>
