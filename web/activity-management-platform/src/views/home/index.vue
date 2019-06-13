<template>
    <div class="index flex">
        <TheSidebar></TheSidebar>
        <div class="main">
            <TheHeader></TheHeader>
            <!-- <TheNavbar></TheNavbar> -->
            <div class="navbar">
                <el-breadcrumb
                    class="self-elementui-breadcrumb"
                    separator-class="el-icon-arrow-right"
                >
                    <template v-for="(item, i) in expandBreadData">
                        <el-breadcrumb-item
                            :key="i"
                            v-if="item.path"
                            :to="{path: item.path}"
                        >{{item.label}}</el-breadcrumb-item>
                        <el-breadcrumb-item :key="i" v-else>{{item.label}}</el-breadcrumb-item>
                    </template>
                </el-breadcrumb>
                <div class="breadcrumb-action">
                    <bread-crumb-action :renderDom="renderElement"></bread-crumb-action>
                </div>
            </div>
            <router-view @changeBreadCrumbAction="changeBreadCrumbAction" class="main-view"></router-view>
        </div>
    </div>
</template>

<script>
import { getUserPower } from "@/utils";
import navMenu from "@/components/navMenu";
import { mapState } from "vuex";
import theHeader from "@/components/the-header";
import store from "@/store";
import api from "@/api";

//用于面包屑中action的使用面板
const BreadCrumbAction = {
    render(createElement) {
        return this.renderDom(this, createElement);
    },
    props: {
        renderDom: {
            type: Function,
            default: (c, h) => h("")
        }
    }
};

export default {
    components: {
        navMenu,
        BreadCrumbAction,
        theHeader
    },
    data() {
        return {
            renderElement: (c, h) => h(""),
            extendFlag: true
        };
    },
    computed: {
        ...mapState([
            "activeIndex",
            "breadData",
            "expandBreadData",
            "moduleIndex",
            "customerCount"
        ]),
        navData() {
            return [];
        }
    },
    watch: {
        $route: function() {
            this.renderElement = (c, h) => h("");
        }
    },
    mounted() {
        api.post("heartbeat").then(res => {});
        this.$bus.$on("changeBreadCrumbAction", this.changeBreadCrumbAction);
    },
    methods: {
        changeBreadCrumbAction(createFn) {
            this.renderElement = createFn;
        }
    }
};
</script>

<style lang="scss" scoped>
.index {
    height: 100%;
    width: 100%;
    .main {
        // width: 87.5%;
        // width: calc(100%-200px);
        flex: 1;
        height: 100%;
        // overflow-y: scroll;
        color: #333b46;
        display: flex;
        flex-direction: column;
        .main-view {
            flex: 1;
            min-height: 0;
            overflow-y: scroll;
        }
    }
}
.flex {
    display: -webkit-box !important;
    display: -ms-flexbox !important;
    display: -webkit-flex !important;
    display: flex !important;
}
.navbar {
    padding: 1.67rem 2.5rem;
    border-bottom: 1px solid #dee3e5;
    color: #333b46;
    font-size: 1.167rem;
    position: relative;
}
.breadcrumb-action {
    position: absolute;
    right: 30px;
    top: 0;
    bottom: 0;
    width: 50%;
    height: 55px;
    line-height: 55px;
    text-align: right;
    .el-button {
        font-size: 12px;
        padding: 10px 15px;
    }
}
</style>
