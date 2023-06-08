import type { AxiosResponse } from 'axios'
import { defineStore } from 'pinia'
import type { UserProperties } from '@/@fake-db/types'
import type { UserParams } from '@/views/apps/user/types'
import axios from '@axios'

let baseUrl = '/api/v1/tags' 

interface Tag {
  id?: number;
  name?: string;
}

interface Tags {
  data: Tag[];
  meta: {
    rowsNumber: number;
    page: number;
    rowsPerPage: number;
  };
}
interface State {
  _allTags: Tag[];
  tags: Tags;
  tag: Tag;
  _loading: boolean;
}

export const useTagListStore = defineStore('TagListStore', {
  id: "tags",
  state: () =>
    <State>{
      _allTags: [],
      tags: {
        data: [],
        meta: {
          rowsPerPage: 5,
          page: 1,
          rowsNumber: 0,
        },
      },
      tag: {},
      _loading: false,
    },

  getters: {
    items: (state) => state.tags.data,
    meta: (state) => state.tags.meta,
    loading: (state) => state._loading,
    allTags: (state) => state._allTags,
  },

  actions: {

   async fetch(params: UserParams) { 
      console.log(params);

      this._loading = true;
      let res = await axios.get(baseUrl, { params }) 
      this._loading = false;
      this.tags.data = res.data.data;
      this.tags.meta = res.data.metaData;

    },

  },
})
