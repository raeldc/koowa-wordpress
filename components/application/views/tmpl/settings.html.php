<h2>Settings Page</h2>

<form method="post" action="<?=@route('view=settings&format=html')?>" name="settingsform">
    <?php if (!empty($layouts)): ?>
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
    <?php endif ?>
    <p><input name="save" type="submit" class="button button-primary button-large" value="Save"></p>
    <input type="hidden" name="_action" value="apply" />
    <input type="hidden" name="component" value="<?=@object('dispatcher')->getIdentifier()->package?>" />
    <input type="hidden" name="settings[status]" value="Almost There" />
</form>
