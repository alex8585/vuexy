<script setup lang="ts">
import type { Options } from '@core/types'

import useCrud from '@/composables/useCrud'
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import type { UserProperties } from '@/@fake-db/types'
import { paginationMeta } from '@/@fake-db/utils'
import AddItemDrawer from '@/views/admin/posts/list/AddNewPostDrawer.vue'
import EditItemDrawer from '@/views/admin/posts/list/EditPostDrawer.vue'
import { usePostListStore } from '@/views/admin/posts/usePostListStore'

const itemsTitle ="Posts"
const addNewItemBtnTitle = "Add New Post"
const  baseUrl = '/api/v1/posts';

const headers = [
  { title: 'Title', key: 'title' },
  { title: 'Category', key: 'category.name' },
  { title: 'Actions', key: 'actions', sortable: false },
]


const { addItem, deleteItem, updateItem } = useCrud(baseUrl)

const itemsStore = usePostListStore(baseUrl)
console.log(itemsStore)

const searchQuery = ref('')
const editRow = ref({})
const deletingRow = ref({})

const isAddItemVisible = ref(false)
const isEditItemVisible = ref(false)
const isConfirmDeleteVisible = ref(false)

const options = ref<Options>({
  page: 1,
  itemsPerPage: 5,
  sortBy: [],
  groupBy: [],
  search: undefined,
})

async function fetchItems() {
    await itemsStore.fetch({
        filter: { 's': searchQuery.value },
        page:options.value.page,
        perPage: options.value.itemsPerPage,
        direction:options.value.sortBy[0]?.order,
        sortBy: options.value.sortBy[0]?.key,
    })
}
watchEffect(fetchItems)


const perPageChange = (perPage: int) => {
    options.value.itemsPerPage = parseInt(perPage, 10)
    options.value.page = 1
}

const updateTableOprions = (e) => {
    const {sortBy } = e;

    let key = sortBy?.[0]?.key
    let order = sortBy?.[0]?.order

    let options_order = options.value.sortBy?.[0]?.order
    let options_key = options.value.sortBy?.[0]?.key

    if(order != options_order) {
        if(!order) {
            order = options_order == 'desc' ? 'asc' : 'desc'
            sortBy[0] = {order, key:options_key}
        } 
        options.value.sortBy = sortBy         
    }
}

const onEditButtonClick = (data: UserProperties) => {
    editRow.value = data.raw
    isEditItemVisible.value = true
}

const addItemSubmit = async (data: UserProperties) => {
  await addItem(data)
  fetchItems()
}

const editItemSubmit = async (data: UserProperties) => {
  await updateItem(data)
  fetchItems()
}

const deleteDialog = async (item: any) => {
   isConfirmDeleteVisible.value = true
   deletingRow.value = item.raw
}

const onDeleteItem = async (confirm: boolean) => {
  if(confirm) {
      await deleteItem(deletingRow.value.id)
      fetchItems()
  }
}

</script>

<template>
  <section>
    <VRow>

      <VCol cols="12">
        <VCard :title="itemsTitle">

          <VDivider />

          <VCardText class="d-flex flex-wrap py-4 gap-4">
            <div class="me-3 d-flex gap-3">
              <AppSelect
                :model-value="options.itemsPerPage"
                :items="[
                  { value: 5, title: '5' },
                  { value: 10, title: '10' },
                  { value: 20, title: '20' },
                  { value: 100, title: '100' },
                  { value: -1, title: 'All' },
                ]"
                style="width: 6.25rem;"
                @update:model-value="perPageChange($event)"
              />
            </div>
            <VSpacer />

            <div class="app-user-search-filter d-flex align-center flex-wrap gap-4 justify-end">
              <!-- 👉 Search  -->
              <div style="inline-size: 10rem;">
                <AppTextField
                  v-model="searchQuery"
                  placeholder="Search"
                  density="compact"
                />
              </div>


              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddItemVisible = true"
              >
                {{addNewItemBtnTitle}}
                
              </VBtn>

            </div>
          </VCardText>

          <VDivider />

          <VDataTableServer
            v-model:items-per-page="options.itemsPerPage"
            :items="itemsStore.items"
            :items-length="itemsStore.rowsNumber"
            :headers="headers"
            class="text-no-wrap"
            @update:options="updateTableOprions($event)"
            
          >


            <!-- Actions -->
            <template #item.actions="{ item }">
              <IconBtn @click="deleteDialog(item)">
                <VIcon icon="tabler-trash" />
              </IconBtn>

              <IconBtn>
                <VIcon 
                @click="onEditButtonClick(item)"
                icon="tabler-edit" />
              </IconBtn>


            </template>

            <!-- pagination -->
            <template #bottom>
              <VDivider />
              <div class="d-flex align-center justify-sm-space-between justify-center flex-wrap gap-3 pa-5 pt-3">
                <p class="text-sm text-disabled mb-0">
                  {{ paginationMeta(options, itemsStore.rowsNumber) }}
                </p>

                <VPagination
                  v-model="options.page"
                  :length="Math.ceil(itemsStore.rowsNumber / options.itemsPerPage)"
                  :total-visible="$vuetify.display.xs ? 1 : Math.ceil( itemsStore.rowsNumber / options.itemsPerPage)"
                >
                  <template #prev="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      Previous
                    </VBtn>
                  </template>

                  <template #next="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      Next
                    </VBtn>
                  </template>
                </VPagination>
              </div>
            </template>

          </VDataTableServer>
          <!-- SECTION -->
        </VCard>

        <AddItemDrawer
          v-model:isDrawerOpen="isAddItemVisible"
          @submit="addItemSubmit"
        />
        <EditItemDrawer
          v-model:row="editRow"
          v-model:isDrawerOpen="isEditItemVisible"
          @submit="editItemSubmit"
        />
        <ConfirmDialog
            v-model:isDialogVisible="isConfirmDeleteVisible"
            :confirmation-question="'Are you sure you want to delete item: \'' + deletingRow.title + '\'?' "
            :show-on-cancel="false"
            :show-on-confirm="false"
            @confirm="onDeleteItem($event)"
         />
      </vcol>
    </vrow>
  </section>
</template>

<style lang="scss">
.app-user-search-filter {
  inline-size: 31.6rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
}
</style>
