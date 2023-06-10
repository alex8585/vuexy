import type { AxiosResponse } from "axios";
import { defineStore } from "pinia";
import type { UserProperties } from "@/@fake-db/types";
import type { UserParams } from "@/views/apps/user/types";
import axios from "@axios";

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

export const useTagListStore = function (baseUrl: string = '') {
  return defineStore("TagListStore", {
    id: "tags",
    state: () =>
      <State>{
        _loading: false,
        _allItems: [],
        _item: {},
        _items: [],
        _meta: {
          rowsPerPage: 5,
          page: 1,
          rowsNumber: 0,
        },
      },

    getters: {
      items: (state) => state._items,
      meta: (state) => state._meta,
      loading: (state) => state._loading,
      allTags: (state) => state._allTags,
      rowsNumber: (state) => state._meta.rowsNumber,
      allItems: (state) => state._allItems
    },

    actions: {
      async fetch(params: UserParams) {
        console.log(params);

        this._loading = true;
        let res = await axios.get(baseUrl, { params });
        this._items = res.data.data;
        this._meta = res.data.metaData;
        this._loading = false;
      },

      async getAllItems() {
        const allUrl = `${baseUrl}?perPage=-1`;
        this._loading = true;
        const res = await axios.get(allUrl);
        this._allItems = res.data.data;
        this._loading = false;
      },
    },
  })();
};
