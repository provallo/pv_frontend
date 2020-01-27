<template>
    <div class="language-selector row">
        <v-select :data="languages" v-model="languageID" displayField="name" valueField="id" class="flex" />
    </div>
</template>

<script>
export default {
    data() {
        return {
            languages: [],
            languageID: null
        }
    },
    watch: {
        languageID(languageID) {
            let me = this

            me.$emit('change', languageID)
        }
    },
    mounted() {
        let me = this

        me.load()
    },
    methods: {
        load() {
            let me = this

            me.$models.language.list().then(items => {
                me.languages = items

                if (me.languages.length > 0) {
                    me.languageID = me.languages[0].id
                }
            })
        }
    }
}
</script>