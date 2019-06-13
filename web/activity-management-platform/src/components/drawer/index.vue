<template>
    <div class="u-drawer" :class="{open:visible}">
        <div class="u-drawer-mask" @click.stop="handleClose"></div>
        <div class="u-drawer-content-wrapper" :style="{width,transform: visible ? '' : 'translateX(100%)'}">
            <div class="u-drawer-content">
                <div class="u-drawer-wrapper-body">
                    <div class="u-drawer-header">
                        <div class="u-drawer-title">{{title}}</div>
                    </div>
                    <el-button v-if="closable" @click.stop="handleClose" class="u-drawer-close"
                               icon="el-icon-close"></el-button>
                    <div class="u-drawer-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "drawer",
        props: {
            appendBody: {
                type: Boolean,
                default: true,
            },
            title: {
                type: String,
                default: '',
            },
            width: {
                type: [String],
                default: '520px',
            },
            placement: {
                type: String,
                default: 'right',
            },
            visible: {
                type: Boolean,
                default: false,
            },
            closable: {
                type: Boolean,
                default: true,
            },
            onClose: {
                type: Function,
                default: () => {
                }
            }
        },
        data() {
            return {
                isOpenDrawer: false,
            }
        },
        mounted() {
            if (this.appendBody) {
                document.body.appendChild(this.$el);
            }

        },
        destroyed() {
            if (this.appendToBody && this.$el && this.$el.parentNode) {
                this.$el.parentNode.removeChild(this.$el);
            }
        },
        methods: {
            handleClose() {
                this.$emit('update:visible', false);
            },
        }
    }
</script>

<style scoped lang="scss">
    @keyframes drawerFadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: .3;
        }
    }

    .u-drawer {
        position: fixed;
        top: 0;
        width: 0;
        height: 100%;
        z-index: 1000;
        transition: all .3s linear;
        .u-drawer-mask {
            position: fixed;
            width: 100%;
            height: 0;
            opacity: 0;
            background-color: rgba(0, 0, 0, 0.65);
            filter: alpha(opacity=50);
            -webkit-transition: opacity .3s linear, height 0s ease .3s;
            transition: opacity .3s linear, height 0s ease .3s;
        }
        &.open {
            width: 100%;
            .u-drawer-mask {
                opacity: .3;
                height: 100%;
                -webkit-animation: drawerFadeIn .3s cubic-bezier(.7, .3, .1, 1);
                animation: drawerFadeIn .3s cubic-bezier(.7, .3, .1, 1);
                -webkit-transition: none;
                transition: none;
            }
        }
        .u-drawer-content-wrapper {
            position: fixed;
            right: 0;
            height: 100%;
            box-sizing: border-box;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15);
            transition: transform .3s cubic-bezier(.9, 0, .3, .7), -webkit-transform .3s cubic-bezier(.9, 0, .3, .7);

        }
        .u-drawer-content {
            position: relative;
            width: 100%;
            height: 100%;
            border: 0;
            z-index: 1;
            background-color: #fff;
            background-clip: padding-box;
        }
        .u-drawer-wrapper-body {
            overflow: auto;
            height: 100%;
        }
        .u-drawer-header {
            padding: 16px 24px;
            border-radius: 4px 4px 0 0;
            background: #fff;
            color: rgba(0, 0, 0, 0.65);
            border-bottom: 1px solid #e8e8e8;
        }
        .u-drawer-title {
            margin: 0;
            font-size: 16px;
            line-height: 22px;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.85);
        }
        .u-drawer-close {
            border: 0;
            padding: 0;
            background: transparent;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 10;
            font-weight: 700;
            text-decoration: none;
            -webkit-transition: color 0.3s;
            transition: color 0.3s;
            color: rgba(0, 0, 0, 0.45);
            display: block;
            font-style: normal;
            text-align: center;
            text-transform: none;
            text-rendering: auto;
            width: 56px;
            height: 56px;
            line-height: 56px;
            font-size: 16px;
        }
        .u-drawer-body {
            padding: 24px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }
    }
</style>