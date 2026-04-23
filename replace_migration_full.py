import re

with open('inc/migration-news.php', 'r') as f:
    content = f.read()

# Duplicate check key
content = re.sub(
    r"'meta_key'\s*=>\s*'_vecchio_id',",
    r"'meta_key'   => '_old_id_art',",
    content
)

# post_data (title and content)
content = re.sub(
    r"'post_title'\s*=>\s*wp_strip_all_tags\(\s*\$safe_title\s*\),",
    r"'post_title'    => $safe_title,",
    content
)

# Author mapping
author_mapping = """        // Autore mapping
        $author_id = get_current_user_id();
        $author_name = '';
        if ( isset( $art['autore'] ) ) {
            $author_row = $legacy_db->get_row( $legacy_db->prepare( "SELECT * FROM tbl_autori WHERE id_autore = %d", intval( $art['autore'] ) ), ARRAY_A );
            if ( ! empty( $author_row ) ) {
                if ( isset( $author_row['nome'] ) ) {
                    $author_name = $author_row['nome'];
                } elseif ( isset( $author_row['autore'] ) ) {
                    $author_name = $author_row['autore'];
                } else {
                    $author_name = reset( $author_row );
                }
            }
        }

        $post_data = array("""
content = content.replace(r"$post_data = array(", author_mapping)

# Category and meta fields mappings
cat_mapping = """            // Categoria mapping
            if ( isset( $art['catg'] ) && ! empty( $art['catg'] ) ) {
                $term = term_exists( $art['catg'], 'category' );
                if ( ! $term ) {
                    $term = wp_insert_term( $art['catg'], 'category' );
                }
                if ( ! is_wp_error( $term ) && isset( $term['term_taxonomy_id'] ) ) {
                    wp_set_post_categories( $post_id, array( $term['term_taxonomy_id'] ) );
                }
            }

            update_post_meta( $post_id, '_old_id_art', intval( $art['id_art'] ) );"""

content = re.sub(r"update_post_meta\(\s*\$post_id,\s*'_vecchio_id',\s*intval\(\s*\$art\['id_art'\]\s*\)\s*\);", cat_mapping, content)

content = re.sub(r"update_post_meta\(\s*\$post_id,\s*'_vecchio_cat',\s*intval\(\s*\$art\['cat'\]\s*\)\s*\);\s*", "", content)

# Video mapping logic
content = re.sub(
    r"""            // Gestione video \(Sideload opzionale o semplice url linking\)
            if \( ! empty\( \$art\['video'\] \) \) \{
                update_post_meta\( \$post_id, '_news_video_url', esc_url_raw\( home_url\( '/public/video/' \. ltrim\( \$art\['video'\], '/' \) \) \) \);
            \} else \{
                update_post_meta\( \$post_id, '_news_video_url', '' \);
            \}\s+// Gestione video \(Sideload opzionale o semplice url linking\)
            if \( ! empty\( \$art\['video'\] \) \) \{
                update_post_meta\( \$post_id, '_news_video_url', esc_url_raw\( home_url\( '/public/video/' \. ltrim\( \$art\['video'\], '/' \) \) \) \);
            \} else \{
                update_post_meta\( \$post_id, '_news_video_url', '' \);
            \}""",
    r"""            // Gestione video (Sideload opzionale o semplice url linking)
            if ( ! empty( $art['video'] ) ) {
                if ( strpos( $art['video'], '<iframe' ) !== false ) {
                    update_post_meta( $post_id, '_news_video_data', $art['video'] );
                } else {
                    update_post_meta( $post_id, '_news_video_data', esc_url_raw( home_url( '/public/video/' . ltrim( $art['video'], '/' ) ) ) );
                }
            } else {
                update_post_meta( $post_id, '_news_video_data', '' );
            }""",
    content
)

# Extra Image Path
content = re.sub(
    r"update_post_meta\(\s*\$post_id,\s*'_news_extra_image',\s*esc_url_raw\(\s*\$img2_url\s*\)\s*\);",
    r"update_post_meta( $post_id, '_news_extra_image_path', esc_url_raw( $img2_url ) );",
    content
)
content = re.sub(
    r"update_post_meta\(\s*\$post_id,\s*'_news_extra_image',\s*''\s*\);",
    r"update_post_meta( $post_id, '_news_extra_image_path', '' );",
    content
)

# Author Name meta saving
author_save = """            // Salva autore
            if ( ! empty( $author_name ) ) {
                update_post_meta( $post_id, '_news_author_name', sanitize_text_field( $author_name ) );
            }

            // F. Importazione Immagine in Evidenza"""
content = content.replace("// F. Importazione Immagine in Evidenza", author_save)

# Redirect and Align meta logic
content = re.sub(
    r"nel database di WordPress usando il postmeta '_vecchio_id'\.",
    r"nel database di WordPress usando il postmeta '_old_id_art'.",
    content
)
content = re.sub(
    r"'key'\s*=>\s*'_vecchio_id',",
    r"'key'     => '_old_id_art',",
    content
)

content = re.sub(
    r"'_news_video_url',\s*'_news_extra_image',",
    r"'_news_video_data',\n        '_news_extra_image_path',\n        '_news_author_name',\n        '_old_id_art',",
    content
)

# Remove trailing broken didascalie map duplicated at bottom of the map variables block
content = re.sub(
    r"""            update_post_meta\( \$post_id, '_news_dida_3', sanitize_text_field\( \$safe_dida3 \) \);.*// Gestione didascalie
            update_post_meta\( \$post_id, '_news_dida_1', sanitize_text_field\( isset\(\$art\['dida1'\]\) \? \$art\['dida1'\] : '' \) \);
            update_post_meta\( \$post_id, '_news_dida_2', sanitize_text_field\( isset\(\$art\['dida2'\]\) \? \$art\['dida2'\] : '' \) \);
            update_post_meta\( \$post_id, '_news_dida_3', sanitize_text_field\( isset\(\$art\['dida3'\]\) \? \$art\['dida3'\] : '' \) \);""",
    r"""            update_post_meta( $post_id, '_news_dida_3', sanitize_text_field( $safe_dida3 ) );""",
    content,
    flags=re.DOTALL
)

with open('inc/migration-news.php', 'w') as f:
    f.write(content)
