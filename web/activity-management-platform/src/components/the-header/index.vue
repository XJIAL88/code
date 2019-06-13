<template>
    <div class="header flex-vertical-center">
        <h1 class="flex-fill title">彩之云活动管理平台</h1>
        <div class="user">
            <div class="user-name flex-center">
                {{getUserName}}
                <i class="el-icon-arrow-down el-icon--right"></i>
                <div class="dropdown">
                    <!-- <div
                        class="item"
                        @click="$router.push({name:'AdminDetail',params: {id: getUserId}})"
                    >
                        <p>个人信息</p>
                    </div>-->
                    <div class="item" @click="logout">
                        <p>退出</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { getUserName, getUserId, removeUserInfo } from "@/utils";
import { API_CONFIG } from "@/api/config";
import api from "@/api";
import axios from "axios";

export default {
    name: "TheHeader",
    data() {
        return {};
    },
    methods: {
        logout() {
            this.$confirm("确定退出吗?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    api.post("oauthLogoutUrl").then(res => {
                        removeUserInfo();
                        axios.defaults.withCredentials = true;
                        if (res.data.includes("http:")) {
                            res.data = res.data.split("http:")[1];
                        }
                        api.get(
                            res.data,
                            {},
                            {
                                baseURL: ""
                                // headers: { "Access-Control-Allow-Origin": "*" }
                            }
                        ).then(_ => {
                            window.location.href =
                                `${API_CONFIG.baseURL}oauthWithPlatform?url=` +
                                encodeURIComponent(window.location.href);
                            axios.defaults.withCredentials = false;
                        });
                    });
                })
                .catch(() => {});
        }
    },
    computed: {
        getUserName,
        getUserId
    }
};
</script>
<style lang="scss" scoped>
.header {
    padding: 0 2.5rem;
    height: 5rem;
    border-bottom: 1px solid #dee3e5;
    .user {
        position: relative;
        width: 10.83rem;
        height: 100%;
        .dropdown {
            display: none;
        }
        &:hover {
            .dropdown {
                display: block;
                position: absolute;
                width: 100%;
                left: 0;
                top: 90%;
                z-index: 2000;
                text-align: center;
                background: #2b303b;
                border-radius: 3px;
                color: #bbc0cb;
                cursor: pointer;
                user-select: none;
                &:before {
                    position: absolute;
                    top: -10px;
                    left: 50%;
                    margin-left: -5px;
                    display: block;
                    content: "";
                    border: 5px solid transparent;
                    border-bottom-color: #2b303b;
                    width: 0;
                    height: 0;
                }
                .item {
                    padding: 0 2.08rem;
                    &:hover {
                        background-color: rgb(34, 38, 47);
                        color: #fff;
                    }
                    p {
                        padding: 0.92rem 0;
                        border-bottom: 1px solid #bbc0cb;
                    }
                    &:last-child {
                        p {
                            border-bottom: 0;
                        }
                    }
                }
            }
        }
    }
    h1.title {
        font-size: 1.67rem;
        color: #333b46;
    }
    .user-name {
        cursor: pointer;
        height: 100%;
        font-size: 1rem;
        color: #4a4a4a;
    }
}
</style>
