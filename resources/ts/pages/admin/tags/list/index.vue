<script setup lang="ts">
import { VDataTableServer } from 'vuetify/labs/VDataTable'
import type { UserProperties } from '@/@fake-db/types'
import { paginationMeta } from '@/@fake-db/utils'
import AddItemDrawer from '@/views/admin/tags/list/AddNewTagDrawer.vue'
import EditItemDrawer from '@/views/admin/tags/list/EditTagDrawer.vue'
import { useTagListStore } from '@/views/admin/tags/useTagListStore'

import type { Options } from '@core/types'

// 👉 Store
const itemsStore = useTagListStore()
const searchQuery = ref('')
const totalItems = ref(0)
const items = ref<UserProperties[]>([])
const editRow = ref({})

const isAddItemVisible = ref(false)
const isEditItemVisible = ref(false)

const options = ref<Options>({
  page: 1,
  itemsPerPage: 5,
  sortBy: [],
  groupBy: [],
  search: undefined,
})

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Actions', key: 'actions', sortable: false },
]

// 👉 Fetching users
const fetchItems = () => {
  //console.log(sortBy.value)
  itemsStore.fetch({
    filter: { 's': searchQuery.value },
    page:options.value.page,
    perPage: options.value.itemsPerPage,
    direction:options.value.sortBy[0]?.order,
    sortBy: options.value.sortBy[0]?.key,
  }).then(response => {
    items.value = response.data.data
    totalItems.value = response.data.metaData.rowsNumber
    //totalPage.value = response.data.metaData.pages
    //options.value.page = response.data.metaData.page
  }).catch(error => {
    console.error(error)
  })
}

watchEffect(fetchItems)



const onEditButtonClick = (data: UserProperties) => {
    editRow.value = data.raw
    isEditItemVisible.value = true
}

const addItemSubmit = (data: UserProperties) => {
  itemsStore.addItem(data).then(response => {
    fetchItems()
  })
}

const editItemSubmit = (data: UserProperties) => {
  itemsStore.updateItem(data).then(response => {
    fetchItems()
  })
}

const deleteUser = (id: number) => {
  itemsStore.deleteItem(id).then(response => {
    fetchItems()
  })
}

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

</script>

<template>
  <section>
    <VRow>

      <VCol cols="12">
        <VCard title="Tags">

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


              <!-- 👉 Add user button -->
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddItemVisible = true"
              >
                Add New Tag
              </VBtn>

            </div>
          </VCardText>

          <VDivider />

          <!--  v-model:sort-by="sortBy"           v-model:page="options.page" SECTION datatable -->
          <VDataTableServer
            v-model:items-per-page="options.itemsPerPage"
            :items="items"
            :items-length="totalItems"
            :headers="headers"
            class="text-no-wrap"
            @update:options="updateTableOprions($event)"
            
          >


            <!-- Actions -->
            <template #item.actions="{ item }">
              <IconBtn @click="deleteUser(item.raw.id)">
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
                  {{ paginationMeta(options, totalItems) }}
                </p>

                <VPagination
                  v-model="options.page"
                  :length="Math.ceil(totalItems / options.itemsPerPage)"
                  :total-visible="$vuetify.display.xs ? 1 : Math.ceil(totalItems / options.itemsPerPage)"
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

        <!-- 👉 Add New User -->
        <AddItemDrawer
          v-model:isDrawerOpen="isAddItemVisible"
          @submit="addItemSubmit"
        />
        <EditItemDrawer
          v-model:row="editRow"
          v-model:isDrawerOpen="isEditItemVisible"
          @submit="editItemSubmit"
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
