<template>

    <Head title="Manage CMS" />
    <breadcrumb title="Manage CMS" :crumbs="['Dashboard', 'CMS', 'Manage']" />

    <div class="col-md-10 mx-auto">
        <div class="card shadow-lg radius-20">
            <div class="card-body">
                <form @submit.prevent="updateCMS" enctype="multipart/form-data">
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
                                <td>
                                    <input type="text" class="form-control" v-model="form.cms[index].title"
                                        placeholder="Enter CMS Title" />
                                        {{ cms.type }}
                                </td>
                                <td>
                                    <input type="file" class="form-control" @change="updateImage($event, index)" />

                                    <div class="mt-2">
                                        <img v-if="item.preview" :src="item.preview" class="img-thumbnail mt-1"
                                            width="100" />
                                        <img v-else-if="item.original" :src="`/storage/${item.original}`"
                                            class="img-thumbnail mt-1" width="100" />
                                    </div>
                                </td>
                                <td>
                                    <vue-editor v-model="form.cms[index].content" />
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
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { VueEditor } from 'vue3-editor';
import breadcrumb from '@/views/components/layout/breadcrumb.vue';
import FormButton from '@/views/components/form/FormButton.vue';
import ButtonLoader from '@/views/components/form/ButtonLoader.vue';
import route from 'ziggy-js';

const props = defineProps({
    cms: Array,
});

const form = useForm({
    cms: props.cms.map((item) => ({
        id: item.id,
        type: item.type,
        title: item.title ?? '',
        content: item.content ?? '',
        image: null,
        preview: null, // for live preview
        original: item.image ?? null, // for already stored image
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
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<style scoped>
.ql-editor {
    min-height: 200px;
}
</style>
