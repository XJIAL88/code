export const handleSearchParams = {
    data(){
        return {
            pageNumber: 1,
        }
    },
    methods: {
        setParams() {
            const params = this.$store.state.listPageParams[this.$route.name];
            if (params) {
                this.search = params;
            }
            this.pageNumber =  this.search.pageNumber
        },
        saveParams(){
            this.pageNumber = this.search.pageNumber;
            this.$store.commit('saveListPageParams',{name: this.$route.name, params: this.search});
        },
        initParams(){
            const search = {};
            Object.keys(this.search).forEach(key => {
                const value = this.search[key];
                if (key === 'adminId' || key === 'adminToken') {
                    search[key] = value;
                }else if(key === 'startAt' || key === 'endAt'){
                    search[key] = 0;
                }else{
                    search[key] = '';
                }
            });
            this.pageNumber = 1;
            search.pageNumber = 1;
            search.pageSize = 10;
            this.search = search;
        },
        doSearch() {
            this.pageNumber = 1;
            this.search.pageNumber = 1;
            this.loadData();
        },
    }
};
