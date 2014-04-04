<fieldset>
    <legend class="screen-reader-text">Assign Pages to Views</legend>
    <ul>
        <?php for($i = 0; $i < count($layouts); $i++): ?>
        <?php $layout = $layouts->current(); $layouts->next(); ?>
        <li>
            <label>
            <?=wp_dropdown_pages( array( 'name' => 'pages['.(string)$i.'][id]', 'echo' => 0, 'show_option_none' => __( '&mdash; Select a Page &mdash;' ), 'option_none_value' => '0', 'selected' => $layout->id ) )?> will go to 
            <strong><?=$layout->title?>.</strong>
            </label>
            <input type="hidden" name="pages[<?=(string)$i?>][component]" value="<?=$layout->component?>" />
            <input type="hidden" name="pages[<?=(string)$i?>][view]" value="<?=$layout->view?>" />
            <input type="hidden" name="pages[<?=(string)$i?>][layout]" value="<?=$layout->layout?>" />
            <input type="hidden" name="pages[<?=(string)$i?>][query][id]" value="my_id" />
            <input type="hidden" name="pages[<?=(string)$i?>][query][slug]" value="my_slug" />
        </li>
        <?php endfor ?>
    </ul>
</fieldset>