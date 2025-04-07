export interface PostItem {
    id?: number;
    name: string;
    content: string;
    short_description: string;
    seo_title?: string;
    seo_description?: string;
    img: File | string;
    category_id: number;
    author: number;
    created_at: string;
    updated_at: string;
}

export interface PostFilter {
    'name': string;
    'created_at_from': string;
    'created_at_to': string;
    'date_fixed': string;
}

