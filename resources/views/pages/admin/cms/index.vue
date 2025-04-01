<template>

    <Head title="Manage CMS" />
    <breadcrumb title="Manage CMS" :crumbs="['Dashboard', 'CMS', 'Manage']" />

    <div class="col-md-10 mx-auto">
        <div class="card shadow-lg radius-20">
            <div class="card-body">
                <form @submit.prevent="updateCMS">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in form.cms" :key="index">
                                <td>{{ item.title }}</td>
                                <td>
                                    <input type="file" class="form-control" @change="updateImage($event, index)" />
                                    <img v-if="item.preview" :src="item.preview" class="img-thumbnail mt-2"
                                        width="100" />
                                </td>
                                <td>
                                    <vue-editor v-model="item.content" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center mt-3">
                        <FormButton type="submit" class="btn btn-outline-primary btn-block px-5">
                            <ButtonLoader text="Update CMS <i class='fa fa-save'></i>" :loading="form.processing" />
                        </FormButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import breadcrumb from '@/views/components/layout/breadcrumb.vue';
import { VueEditor } from 'vue3-editor';
import { useForm } from '@inertiajs/inertia-vue3';
import { computed, watch } from 'vue';
import FormButton from '@/views/components/form/FormButton.vue';
import ButtonLoader from '@/views/components/form/ButtonLoader.vue';
import route from 'ziggy-js';

const props = defineProps({
    cms: Array,
});

const form = useForm({
    cms: props.cms.map(item => ({
        id: item.id,
        title: item.title,
        content: item.content,
        image: null,
        preview: item.image ? `/storage/${item.image}` : null,
    })),
});

const updateImage = (event, index) => {
    const file = event.target.files[0];
    if (file) {
        form.cms[index].image = file;
        form.cms[index].preview = URL.createObjectURL(file);
    }
};

const updateCMS = () => {
    form.post(route('admin.cms.update'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<style>
.ql-editor {
    min-height: 200px;
}
</style>
