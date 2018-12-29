<template>
    <div class="is--page-view view">
        <v-grid ref="grid" :config="gridConfig" @create="create">
            <div class="grid-item user" slot="item" slot-scope="{ model }"
                 :class="{ active: editingModel && editingModel.id === model.id }">
                <div class="item-meta" @click="edit(model)">
                    <div class="item-label">
                        {{ model.label }}
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
                    <v-checkbox label="Active" v-model="editingModel.active"></v-checkbox>
                </div>
                <div class="form-item">
                    <v-checkbox label="Secure" v-model="editingModel.secure"></v-checkbox>
                </div>
                <div class="form-item">
                    <label for="label">
                        Label
                    </label>
                    <v-input type="text" id="label" v-model="editingModel.label"></v-input>
                </div>
                 <div class="form-item">
                    <label for="host">
                        Host
                    </label>
                    <v-input type="text" id="host" v-model="editingModel.host"></v-input>
                </div>
                 <div class="form-item">
                    <label for="hosts">
                        Hosts
                    </label>
                    <v-input type="textarea" id="hosts" v-model="editingModel.hosts"></v-input>
                </div>
            </v-form>
        </v-detail>
    </div>
</template>

<script>
export default {
    data() {
        let me = this
        
        return {
            gridConfig: {
                model: me.$models.domain
            },
            formButtons: [
                {
                    label: 'Save',
                    primary: true,
                    name: 'submit'
                }
            ],
            editingModel: null
        }
    },
    methods: {
        create () {
            let me = this
            
            me.editingModel = me.$models.domain.create()
            me.$nextTick(() => me.$refs.form.reset())
        },
        edit (model) {
            let me = this
            
            me.editingModel = model
            me.$nextTick(() => {
                me.$refs.form.reset()
            })
        },
        submit ({ setMessage, setLoading, setProgress }) {
            let me = this
            
            setLoading(true)
            me.$models.domain.save(me.editingModel).then(({ success, data, messages }) => {
                if (success) {
                    setMessage('success', 'The domain were saved successfully')
                    setLoading(false)
                    
                    me.editingModel.id = data.id
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
        remove (model) {
            let me = this
            
            me.$models.domain.remove(model).then((success) => {
                if (success) {
                    me.$refs.grid.load()
                    
                    if (me.editingModel && me.editingModel.id === model.id) {
                        me.editingModel = null
                    }
                } else {
                    me.$swal({
                        type: 'error',
                        title: 'Sorry!',
                        text: 'Unfortunately you are not allowed to delete this domain.'
                    })
                }
            }).catch(error => {
                console.log(error)
            })
        }
    }
}
</script>