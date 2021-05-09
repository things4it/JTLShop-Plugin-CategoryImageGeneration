<h2>{__('Re-generate category image')}</h2>

<form method="post">
    {$jtl_token}
    <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">

    <div class="form-group form-row">
        <label for="googleMerchantId" class="col-sm-3 col-form-label">{__('Category-Id')}</label>
        <input type="number" name="categoryId" value="{$googleMerchantId|default:''}" required
               class="form-control col-sm-3" size="10">
    </div>

    <button class="btn btn-primary" type="submit">{__('Re-Generate')}</button>
</form>
