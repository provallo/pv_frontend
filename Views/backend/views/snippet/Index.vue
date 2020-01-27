<template>
    <div class="is--page-view view">
        <v-language-selector @change="onLanguageChanged" ref="languageSelector"/>
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
                        <fa icon="trash"></fa>
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
                    <v-input type="text" id="id" :value="editingModel.id.toString()" readonly></v-input>
                </div>
                <div class="form-item">
                    <label for="name">
                        Name
                    </label>
                    <v-input type="text" id="name" v-model="editingModel.name"></v-input>
                </div>
                <div class="form-item">
                    <label for="value">
                        Value
                    </label>
                    <v-input type="text" id="value" v-model="getTranslated(editingModel).value"></v-input>
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
            languageID: null
        }
    },
    methods: {
        create() {
            let me = this

            me.editingModel = me.$models.snippet.create()
            
            me.$refs.languageSelector.languages.forEach(language => {
                me.editingModel.values.push({
                    languageID: language.id,
                    value: '',
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
        onLanguageChanged(languageID) {
            let me = this
            
            me.languageID = languageID
        },
        getTranslated(model) {
            return model.values.find(t => t.languageID === this.languageID) || model
        }
    }
}
</script>