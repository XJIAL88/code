<template>
    <div class="sidebar">
        <div class="logo-box">
            <div class="logo">
                <img src="./images/logo.png">
            </div>
        </div>
        <div class="menu">
            <navMenu :menu="navData" :activeIndex="activeIndex" @select="handleSelect"></navMenu>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import navMenu from "./navMenu";
export default {
    name: "TheSidebar",
    components: {
        navMenu
    },
    data() {
        return {
            list: []
        };
    },
    computed: {
        ...mapState(["navData", "activeIndex", "breadData", "expandBreadData"])
    },
    methods: {
        handleSelect({ key, keyPath, item }) {
            // console.log('->>>>>>', keyPath, key, item.route, this.activeIndex, location.hash);
            const activeIndex = this.activeIndex.split(":");
            if (key === activeIndex[0]) {
                if (
                    activeIndex.length > 1 &&
                    location.hash.replace("#", "") !== activeIndex[1]
                ) {
                    this.$router.push(activeIndex[1]);
                }
                // return;
            }
            this.$store.commit("setActiveIndex", key);
            this.$store.commit("setBreadData", keyPath);
            if (item.route) {
                this.$router.push({
                    path: item.route.path
                });
            } else {
                this.$router.push({
                    name: key
                });
            }
        }
    }
};
</script>
<style lang="scss" scoped>
.sidebar {
    position: relative;
    // width: 12.5%;
    height: 100%;
    // min-width: 210px;
    width: 200px;
    min-width: 200px;
    z-index: 999;
    background: #264e81;
    color: #bbc0cb;
    user-select: none;
    overflow: hidden;
    .logo-box {
        padding: 2.17rem 2.08rem;
        height: 120px;
        box-sizing: border-box;
        .logo {
            padding: 0 2.08rem;
            border-bottom: 1px solid #626b77;
            img {
                width: 100%;
                margin-bottom: 2.08rem;
            }
        }
    }
    .menu {
        // width: calc(100% + 17px);
        width: 200px;
        height: calc(100% - 120px);
        // overflow-y: scroll;
        font-size: 1.17rem;
        background: #264e81;
        color: #bbc0cb;
        border-right: 0;
        .el-menu {
            border-right: 0;
        }
        /deep/ .el-menu-item.is-active {
            background: rgb(30, 62, 103) !important;
        }
    }
}
</style>
