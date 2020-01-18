<template>
    <div class="domain-selector row">
        <v-select :data="items" v-model="domainID" displayField="label" valueField="id" class="flex" />
        
        <template v-if="languages.length > 0">
            <v-select :data="languages" v-model="languageID" displayField="name"
                      style="width:150px;margin-left:10px;" />
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            domainID: null,

            languages: [],
            languageID: null
        }
    },
    watch: {
        domainID(domainID) {
            let me = this
            let domain = me.items.find(i => i.id === domainID)

            me.languages = domain.languages
            me.languageID = domain.languageID

            me.$emit('change', domainID, me.languageID)
        },
        languageID(languageID) {
            let me = this

            me.$emit('change', me.domainID, languageID)
        }
    },
    mounted() {
        let me = this

        me.load()
    },
    methods: {
        load() {
            let me = this

            me.$models.domain.list().then(items => {
                me.items = items

                if (me.items.length > 0) {
                    me.domainID = me.items[0].id
                }
            })
        }
    }
}
</script>