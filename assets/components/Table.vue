<template>
    <div>
        <v-table class="striped-table shadow mb-8 mt-8" elevation="3">
            <thead>
                <slot name="thead"></slot>
            </thead>
            <tbody>
                <slot name="tbody"></slot>
            </tbody>
        </v-table>
        <slot name="pagination"></slot>
    </div>
</template>
<script>
export default {
    name: "Table",
    data(){
        return {

        } 
    },
    props:{
        header:{
            type: Array,
            required: true,
        },
    } 
}
</script>
<style scoped>

/deep/ .striped-table tbody tr:nth-child(odd), ::v-deep(.striped-table tbody tr:nth-child(odd)){
    background:#f5f5f5;
}

::v-deep(.striped-table tbody tr:nth-child(even)){
    background:#fff;
}
 .shadow {
    box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
    border-radius: 8px;
 } 
</style>