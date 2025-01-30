<template>
    <v-table>
        <thead>
            <slot name="thead"></slot>
        </thead>
        <tbody>
            <slot name="tbody"></slot>
        </tbody>
    </v-table>
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