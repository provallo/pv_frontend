<template>
    <div class="domain-selector">
        <v-select :data="items" v-model="selectedID" displayField="label" valueField="id"></v-select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            selectedID: null
        }
    },
    watch: {
        selectedID (selectedID) {
            let me = this
            
            me.$emit('change', selectedID)
        },
    },
    mounted () {
        let me = this
        
        me.load()
    },
    methods: {
        load () {
            let me = this
            
            me.$models.domain.list().then(items => {
                me.items = items
                
                if (me.items.length > 0) {
                    me.selectedID = me.items[0].id
                }
            })
        }
    }
}
</script>