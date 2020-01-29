<template>
    <div class="is--page-view view">
        <v-grid ref="grid" :config="gridConfig" @create="create">
            <div class="grid-item user" slot="item" slot-scope="{ model }"
                 :class="{ active: editingModel && editingModel.id === model.id }">
                <div class="item-meta" @click="edit(model)">
                    <div class="item-label">
                        {{ model.name }}
                    </div>
                </div>
                <div class="item-actions">
                    <div class="item-action" @click="remove(model)">
                        <fa icon="trash" />
                    </div>
                </div>
            </div>
        </v-grid>
        <v-detail :disabled="!editingModel">
            <v-form v-if="editingModel"
                    @submit="submit" :buttons="formButtons"
                    :style="{ width: '500px' }"
                    ref="form">
                <div class="form-item" v-if="editingModel.id > 0">
                    <label for="id">
                        ID
                    </label>
                    <v-input type="text" id="id" :value="editingModel.id.toString()" readonly />
                </div>
                <div class="form-item">
                    <label for="name">
                        Name
                    </label>
                    <v-input type="text" id="name" v-model="editingModel.name" />
                </div>
                <div class="values-container">
                    <div class="domain-container" v-for="domain in domains">
                        <div class="domain-label">
                            {{ domain.label }} ({{ domain.host }})
                        </div>
                        <div class="language-container" v-for="language in domain.languages">
                            <div class="form-item">
                                <label :for="'value' + domain.id + '' + language.id">
                                    {{ language.name }}
                                </label>
                                <v-input type="textarea" :id="'value' + domain.id + '' + language.id"
                                         v-model="getValue(domain.id, language.id).value" />
                            </div>
                        </div>
                    </div>
                </div>
            </v-form>
        </v-detail>
    </div>
</template>

<script>
import VLanguageSelector from '../../components/LanguageSelector'

export default {
    components: {
        VLanguageSelector
    },
    data() {
        let me = this

        return {
            gridConfig: {
                model: me.$models.snippet
            },
            formButtons: [
                {
                    label: 'Save',
                    primary: true,
                    name: 'submit'
                }
            ],
            editingModel: null,
            domains: []
        }
    },
    mounted() {
        const me = this;
        
        me.$models.domain.list().then(domains => me.domains = domains);
    },
    methods: {
        create() {
            let me = this

            me.editingModel = me.$models.snippet.create()
            
            me.domains.forEach(domain => {
                domain.languages.forEach(language => {
                    me.editingModel.values.push({
                        domainID: domain.id,
                        languageID: language.id,
                        value: ''
                    })
                })
            })

            me.$nextTick(() => me.$refs.form.reset())
        },
        edit(model) {
            let me = this

            me.editingModel = model
            me.$nextTick(() => {
                me.$refs.form.reset()
            })
        },
        submit({ setMessage, setLoading, setProgress }) {
            let me = this

            setLoading(true)
            me.$models.snippet.save(me.editingModel).then(({ success, data, messages }) => {
                if (success) {
                    setMessage('success', 'The snippet were saved successfully')
                    setLoading(false)

                    me.editingModel.id = data.id
                    me.editingModel.values = data.values

                    me.$refs.grid.load()
                } else {
                    setMessage('error', messages[0])
                    setLoading(false)
                }
            }).catch(error => {
                setMessage('error', error.toString())
                setLoading(false)
            })
        },
        remove(model) {
            let me = this

            me.$models.snippet.remove(model).then((success) => {
                if (success) {
                    me.$refs.grid.load()

                    if (me.editingModel && me.editingModel.id === model.id) {
                        me.editingModel = null
                    }
                } else {
                    me.$swal({
                        type: 'error',
                        title: 'Sorry!',
                        text: 'Unfortunately you are not allowed to delete this snippet.'
                    })
                }
            }).catch(error => {
                console.log(error)
            })
        },
        getValue (domainID, languageID) {
            const me = this;
            
            return me.editingModel.values
                .find(v => v.domainID === domainID && v.languageID === languageID);
        }
    }
}
</script>