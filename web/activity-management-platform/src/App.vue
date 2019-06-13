<template>
    <div id="app">
        <router-view/>
    </div>
</template>
<script>
export default {
    name: "App"
};
</script>
<style lang="scss">
@import "@/assets/sass/index.scss";
.user-popover {
    padding: 0;
}

#app {
    font-family: "Microsoft YaHei", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

html,
body,
#app {
    width: 100%;
    height: 100%;
    font-size: 12px;
}
@media screen and (max-width: 1366px) {
    #app {
        width: 1366px;
    }
}
body,
p {
    margin: 0;
}

h1,
h2,
h3,
h4,
h5,
h6 {
    font-weight: normal;
    margin: 0;
}

a {
    text-decoration: none;
    color: #fff;
}

li {
    list-style: none;
}

.page-list {
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 0 30px;
    box-sizing: border-box;
    overflow-y: scroll;
    background: #fff;
    &::-webkit-scrollbar {
        display: none;
        width: 0px; /* remove scrollbar space */
        background: transparent; /* optional: just make scrollbar invisible */
    }
    .page-list-table {
        overflow-y: scroll;
        &::-webkit-scrollbar {
            display: none;
            width: 0px; /* remove scrollbar space */
            background: transparent; /* optional: just make scrollbar invisible */
        }
    }
    .page-list-pagination {
        flex: none;
        padding: 30px 0;
    }
    .flex1 {
        flex: 1;
        min-height: 0;
    }
}

.page-edit {
    padding: 28px 35px;
    height: 100%;
    box-sizing: border-box;
    overflow: auto;
    background: #fff;
    /*&::-webkit-scrollbar {*/
    /*display: none;*/
    /*width: 0px; !* remove scrollbar space *!*/
    /*background: transparent; !* optional: just make scrollbar invisible *!*/
    /*}*/
}

.page-detail {
    padding: 28px 35px;
    height: 100%;
    box-sizing: border-box;
    overflow: auto;
    background: #fff;
    /*&::-webkit-scrollbar {*/
    /*display: none;*/
    /*width: 0px; !* remove scrollbar space *!*/
    /*background: transparent; !* optional: just make scrollbar invisible *!*/
    /*}*/
}

.childPage {
    position: relative;
    padding: 22px 30px;
    background: white;
}

.e-date span.el-input__prefix {
    left: inherit;
    right: 5px;
}

.errorTip {
    font-size: 12px;
    color: #f56c6c;
    padding-left: 9px;
    padding-top: 5px;
}
.el-select {
    width: 100%;
}
.el-cascader {
    width: 100%;
}
</style>
