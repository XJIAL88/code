<template>
    <div>
        <el-time-picker
            is-range
            v-model="value"
            range-separator="-"
            start-placeholder="开始时间"
            end-placeholder="结束时间"
            value-format="HH:mm"
            format="HH:mm"
            style="width:160px"
            @change="onChange"
        ></el-time-picker>
    </div>
</template>

<script>
export default {
    name: "timePicker",
    props: {
        time: {
            type: String,
            default: []
        }
    },
    data() {
        return {
            value: ["00:00", "23:00"]
        };
    },
    watch: {
        time(val) {
            if (val) {
                this.$set(this.value, 1, val.split("-")[1]);
                this.$set(this.value, 0, val.split("-")[0]);
            } else {
                this.value = ["00:00", "23:00"];
            }
        }
    },
    created() {
        if (this.time) {
            this.$set(this.value, 1, this.time.split("-")[1]);
            this.$set(this.value, 0, this.time.split("-")[0]);
        }
    },
    methods: {
        onChange(val) {
            if (val) {
                this.time = val;
                this.$emit("update:time", val.join("-"));
            }
        }
    }
};
</script>
