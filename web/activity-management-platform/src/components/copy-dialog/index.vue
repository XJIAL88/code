<template>
    <div>
        <el-dialog
            :visible.sync="visible"
            :before-close="()=>{$emit('update:visible',false)}"
            width="800px"
            center
        >
            <p>导出任务已经创建并执行，</p>
            <p>请先复制以下链接并在约{{time}}分钟后于浏览器打开进行下载</p>
            <p style="padding-top: 20px;">
                下载链接：{{url}}
                <span
                    id="order-list-btn-clone"
                    style="cursor: pointer"
                    data-clipboard-action="copy"
                    :data-clipboard-text="url"
                >
                    <img src="./icon-copy.svg" style="width:1.2rem;" alt>
                </span>
            </p>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="()=>{$emit('update:visible',false)}">我知道了</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import Clipboard from "clipboard";
export default {
    name: "copyDialog",
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        time: {
            type: [String, Number],
            default: ""
        },
        url: {
            type: String,
            default: ""
        }
    },
    mounted() {
        const iOS =
            /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (iOS) {
            const btn = document.querySelector("#order-list-btn-clone");
            btn.addEventListener("click", () => {
                const input = document.createElement("input");
                input.setAttribute("readonly", "readonly");
                input.setAttribute("value", this.number);
                // input.style.opacity = '0';
                document.body.appendChild(input);
                input.setSelectionRange(0, 9999);
                if (document.execCommand("copy")) {
                    document.execCommand("copy");
                    this.$message({
                        message: "复制成功",
                        type: "success"
                    });
                }
                document.body.removeChild(input);
            });
        } else {
            this.copy = new Clipboard("#order-list-btn-clone");
            this.copy.on("success", e => {
                this.$message({
                    message: "复制成功",
                    type: "success"
                });
            });
        }
    }
};
</script>

