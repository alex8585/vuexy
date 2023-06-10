<script setup lang="ts">
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
// eslint-disable-next-line @typescript-eslint/consistent-type-imports
import type { VForm } from "vuetify/components/VForm";

import type { UserProperties } from "@/@fake-db/types";
import { emailValidator, requiredValidator } from "@validators";

import { useCategoriesListStore } from "@/views/admin/categories/useCategoriesListStore";
import { useTagListStore } from "@/views/admin/tags/useTagListStore";

interface Emit {
  (e: "update:isDrawerOpen", value: boolean): void;
  (e: "submit", value: UserProperties): void;
}

interface Props {
  isDrawerOpen: boolean;
  row: Object;
}

const formTitle = "Edit Post";

const props = defineProps<Props>();
const emit = defineEmits<Emit>();

const isFormValid = ref(false);
const refForm = ref<VForm>();

const catsStore = useCategoriesListStore();
const tagsStore = useTagListStore();

const form = ref({
  id: null,
  tags: [],
  title: null,
  description: null,
  category: null
});

let tagOptions = computed(() => {
  let options = [];
  for (const tag of [...tagsStore.allItems] as Array<TagType>) {
    let option = {
      title: tag.name,
      value: tag.id,
    };
    options.push(option);
  }
  return options;
});

let catsOptions = computed(() => {
  let options = [];
  options.push({ title: "Default", value: null });
  for (const cat of [...catsStore.allItems] as Array<TagType>) {
    let option = {
      title: cat.name,
      value: cat.id,
    };
    options.push(option);
  }
  return options;
});

const setRow = () => {
  if(!props.isDrawerOpen) {
    return
  }

  console.log(props.row)
  let tags = []
  if (props.row.tags) {
      for (const tag of props.row.tags as Array<TagType>) {
        let option = {
          title: tag.name,
          value: tag.id,
        };
        tags.push(option);

      }
  }
  let category = props.row.category.id
  form.value = { ...props.row, tags,category }
};

watch([props], setRow)

//watchEffect(setRow);

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);

  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
  });
};

const handleDrawerModelValueUpdate = (val: boolean) => {
  emit("update:isDrawerOpen", val);
};

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit("submit", form.value);
      emit("update:isDrawerOpen", false);
      nextTick(() => {
        refForm.value?.reset();
        refForm.value?.resetValidation();
      });
    }
  });
};
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      :title="formTitle"
      @cancel="closeNavigationDrawer"
    />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="form.title"
                  :rules="[requiredValidator]"
                  label="Title"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.description"
                  :rules="[requiredValidator]"
                  label="Description"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  name="category"
                  label="Category"
                  v-model="form.category"
                  :items="catsOptions"
                  chips
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  name="tags"
                  label="Tags"
                  v-model="form.tags"
                  :items="tagOptions"
                  multiple
                  chips
                />
              </VCol>


              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="secondary"
                  @click="closeNavigationDrawer"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
