export interface MapItem {
    id: number;
    name: string;
    name_dir: string;
    url_img: File | string;
    map_archive?: File | string;
    total_player: number;
    size: string;
    map_rate: number;
    rate: number;
    version: number;
    author?: string;
    created_at?: string;
    updated_at?: string;
}
