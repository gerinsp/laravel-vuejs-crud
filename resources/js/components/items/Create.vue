<template>
    <div class="row justify-content-start">
        <div class="col-md-12 col-xl-6">
            <div class="card">
                <div class="card-header">Add Items</div>

                <div class="card-body">
                    <form action="#" @submit.prevent="createData">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" v-if="errors.hasOwnProperty('name')" class="form-control is-invalid" id="name" name="name" v-model="data.name" aria-describedby="errName" required>
                        <input type="text" v-else class="form-control" id="name" name="name" v-model="data.name" required>
                        <div v-if="errors.hasOwnProperty('name')" id="errName" class="invalid-feedback" >
                            {{ errors['name'][0] }}
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <select class="form-control" id="type_id" @change="handleChange" required>
                            <option selected>-Pilih Type-</option>
                            <option v-for="(type, index) in types" :key="index" :value="type.id">{{ type.name }}</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <img class="img-preview img-fluid mb-3 col-sm-5">
                        <input type="file" v-if="errors.hasOwnProperty('image')" class="form-control is-invalid" id="image" name="image" aria-describedby="errImage" @change="handleFileUpload" required>
                        <input type="file" v-else class="form-control" id="image" name="image"  @change="handleFileUpload" required>
                        <div v-if="errors.hasOwnProperty('image')" id="errImage" class="invalid-feedback" >
                            {{ errors['image'][0] }}
                        </div>
                    </div>
                    <div class="btn-group mt-3" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        <button type="button" class="btn btn-sm btn-secondary" @click="cancelCreate">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: [],
            types: [],
            errors: {}
        }
    },
    props: {
        datas: Array
    },
    mounted() {
        console.log('Component mounted.')
        this.getAllType()
        console.log(this.data)
    },
    methods: {
        createData() {
            const formData = new FormData()
            formData.append('name', this.data.name)
            formData.append('type_id', this.data.type_id)
            formData.append('image', this.data.image)
            axios.post('/api/items', formData)
            .then((response) => {
                console.log(response.data.data)
                this.$emit('create', response.data);
                this.$emit('getAllData')
                this.datas.push(response.data.data);
                this.data = null;
                this.cancelCreate();
        }).catch((err) => {
             if (err.response.status === 422) {
                this.errors = err.response.data.errors;
             }
        });

        },
        cancelCreate() {
            this.$emit('cancelCreate');
        },
        getAllType()
        {
            axios.get('/api/get-type')
            .then(res => {
                this.types = res.data;
                console.log(res.data)
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
            this.data.image = event.target.files[0];
        },
        handleChange(event) {
            this.data.type_id = event.target.value;
            
            console.log(this.data.type_id)
        }
    }
}
</script>
