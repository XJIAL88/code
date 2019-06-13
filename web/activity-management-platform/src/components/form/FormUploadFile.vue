<template>
    <div class="component-form-upload-file">
        <div v-for="(item, index) in fileList"
             class="img"
             :key="index">
            <div v-if="item.status==='success'">
                <img :src="item.url | formatUploadUrl | formatImage(128, 128)"
                     class="form-upload-image-img">
                <i class="close el-icon-close"
                   @click="handleRemoveFile(index)"></i>
            </div>
            <div v-else>
                <el-progress type="circle"
                             :percentage="item.percentage"
                             :status="item.status"></el-progress>
            </div>
        </div>
        <el-upload list-type="picture-card"
                   :data="{fileName:'file', fileType:type}"
                   :action="action"
                   :multiple="true"
                   :show-file-list="false"
                   :file-list="fileList"
                   :on-change="onChange"
                   :on-success="onSuccess">
            <i class="el-icon-plus"></i>
        </el-upload>
    </div>
</template>

<script>
    import {UPLOAD_DOMAIN} from '@/api/config'
    export default {
        name: "FormUploadFile",
        props: {
            // 1-个人征信报告
            // 2-户口本
            // 3-结婚证
            // 4-企业营业执照
            // 5-企业信用报告
            // 6-法人人行征信报告
            // 6-企业名下对应银行负债表
            // 7-企业审计或财务表
            // 8-企业场地租赁资料
            // 9-身份证
            // 10其他
            type: {
                type: [String,Number],
                default: '',
            },
            customerId:{
                type: [String,Number],
                default:''
            },
            desc:{
                type: String,
                default: '',
            },
            total: {
                type: [Number, String],
                default: 1
            },
        },
        data(){
            return {
                action: UPLOAD_DOMAIN + "customerAttachmentInsert",
                fileList: [],
                inited: false
            }
        },
        methods:{
            onChange(){

            },
            onSuccess(){

            },
            handleRemoveFile(){

            }
        }
    }
</script>

<style scoped lang="scss">
.component-form-upload-file {

}
</style>