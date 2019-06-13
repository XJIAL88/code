<template>
    <el-dialog custom-class="file-preview-dialog"
               :title="title"
               :visible.sync="visible"
               :modal-append-to-body="false"
               :append-to-body="true"
               :before-close="()=>{$emit('update:visible',false)}"
               width="80%"
               top="5vh">
        <div class="content">
            <iframe v-if="office[getFileSuffix(fileSrc)]"
                    :src="'https://view.officeapps.live.com/op/view.aspx?src='+formatUploadUrl(fileSrc)"
                    width='100%'
                    height='700px'
                    frameborder='1'>
            </iframe>
            <embed v-if="getFileSuffix(fileSrc) === 'pdf'"
                   type="application/pdf"
                   :src="fileSrc | formatUploadUrl"
                   width="100%" height="700px">
            <img v-if="image[getFileSuffix(fileSrc)]"
                 style="max-width: 100%"
                 :src="fileSrc | formatUploadUrl" alt="">
        </div>
    </el-dialog>
</template>

<script>
    import {getFileSuffix} from '@/utils'
    import {UPLOAD_DOMAIN} from '@/api/config'

    export default {
        name: "file-preview-dialog",
        props: {
            visible: {
                type: Boolean,
                default: false,
            },
            title: {
                type: String,
                default: '',
            },
            fileSrc: {
                type: String,
                default: '',
            },
        },
        data() {
            return {
                image: {'jpg': true, 'jpeg': true, 'png': true},
                office: {'doc': true, 'docx': true, 'xls': true, 'xlsx': true, 'csv': true},
                word: {'doc': true, 'docx': true},
                excel: {'xls': true, 'xlsx': true, 'csv': true},
            }
        },
        methods: {
            getFileSuffix,
            formatUploadUrl(url) {
                if (url) {
                    let re = new RegExp("^(http|https)://.*$");
                    if (re.test(url)) {
                        return url;
                    } else {
                        return UPLOAD_DOMAIN + url;
                    }
                } else {
                    return url;
                }
            },
        }
    }
</script>

<style scoped lang="scss">
.content {
    height: 700px;
    overflow-y: scroll;
}
</style>