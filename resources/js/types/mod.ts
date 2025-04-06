export interface ModItem {
    id: number;
    name: string;
    name_dir: string;
    url_img: File | string;
    mod_archive?: File | string;
    type: number;
    description: string;
    mod_rate: number;
    author?: string;
    version: number;
    created_at?: string;
    updated_at?: string;
}
