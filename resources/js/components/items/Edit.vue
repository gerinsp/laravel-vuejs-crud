<template>
    <div class="row justify-content-start">
        <div class="col-md-12 col-xl-6">
            <div class="card">
                <div class="card-header">Edit Items</div>

                <div class="card-body">
                    <form action="#" @submit.prevent="updateData" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                        <input type="text" v-if="errors.hasOwnProperty('name')" class="form-control is-invalid" id="name" name="name" v-model="editedData.name" aria-describedby="errName">
                        <input type="text" v-else class="form-control" id="name" name="name" v-model="editedData.name">
                        <div v-if="errors.hasOwnProperty('name')" id="errName" class="invalid-feedback" >
                            {{ errors['name'][0] }}
                        </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="type">Type</label>
                            <select class="form-control" id="type_id" @change="handleChange">
                                <option disabled value="">-Pilih Type-</option>
                                <option v-for="(type, index) in types" :key="index" :value="type.id" :selected="type.id === editedData.type_id">{{ type.name }}</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <img v-if="editedData.image" :src="'storage/' + editedData.image" class="img-preview img-fluid mb-3 col-sm-5">
                            <img v-else class="img-preview img-fluid mb-3 col-sm-5">
                            <input type="file" v-if="errors.hasOwnProperty('image')" class="form-control is-invalid" id="image" name="image" aria-describedby="errImage" @change="handleFileUpload">
                            <input type="file" v-else class="form-control" id="image" name="image"  @change="handleFileUpload">
                            <div v-if="errors.hasOwnProperty('image')" id="errImage" class="invalid-feedback" >
                                {{ errors['image'][0] }}
                            </div>
                        </div>
                        <div class="btn-group mt-3" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary" @click="cancelEdit">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['selectedData'],
        data() {
            return {
                editedData: { ...this.selectedData },
                types: [],
                errors: {}
            }
        },
        mounted() {
            this.getAllType()
            console.error(this.editedData)
        },
        methods: {
            updateData() {
                const formData = new FormData()
                formData.append('name', this.editedData.name)
                formData.append('type_id', this.editedData.type_id)
                formData.append('image', this.editedData.image)
                formData.append('_method', 'PUT')
                console.log(formData.get('name'))
                console.log(formData.get('type_id'))
                axios.post(`/api/items/${this.selectedData.id}`, formData)
                    .then(response => {
                        console.log(response.data)
                        this.$emit('updated', response.data);
                        this.$emit('getAllData');
                        this.cancelEdit();
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
            },
            cancelEdit() {
                this.$emit('cancelEdit');
            },
            getAllType()
            {
                axios.get('/api/get-type')
                .then(res => {
                    this.types = res.data;
                })
                .catch(err => {
                    console.error(err)
                });
            },
            previewImage() {
                const image = document.querySelector('#image');
                const imgPreview = document.querySelector('.img-preview');
                imgPreview.style.display = 'block';
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);
                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            },
            handleFileUpload(event) {
                this.previewImage()
                console.log(event.target.files[0])
                this.editedData.image = event.target.files[0];
            },
            handleChange(event) {
                this.editedData.type_id = event.target.value;
                
                console.log(this.editedData.type_id)
            }
        }
    }
</script>