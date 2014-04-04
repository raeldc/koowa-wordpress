<div class="bs">
    <div class="settings-body center-block">
        <h2 class="settings-header">
        <span class="glyphicon glyphicon-cog"></span><br>
        <?= @translate(ucfirst(@object('dispatcher')->getIdentifier()->package).' Settings') ?>
        </h2>
        <form method="post" action="<?=@route('view=settings&format=html')?>" name="settingsform" class="form-horizontal settings-form" role="form">
            <?php if ($display_layouts): ?>
                <?= @import('viewlayouts.html') ?>
            <?php endif ?>

            <?= $settings_template ?>

            <?php if ($display_actions): ?>
            <div class="action-buttons">
            <input name="_savebutton" type="submit" class="button button-primary button-large" value="<?= @translate('Save') ?>">
            <input type="hidden" name="_action" value="apply" />
            <input type="hidden" name="component" value="<?= $settings->component ?>" />
            </div>
            <?php endif ?>
        </form>
    </div>
</div>