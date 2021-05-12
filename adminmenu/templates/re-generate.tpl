<div class="first">
    <div class="subheading1">{__('Via kKategorie')}</div>
    <hr class="mb-3">

    <form method="post">
        {$jtl_token}
        <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">

        <div class="form-group form-row align-items-center">
            <label class="col col-sm-4  col-form-label text-sm-right" for="googleMerchantId">{__('Category-Id')}</label>
            <div class="col-sm pr-sm-8 order-last">
                <input type="number" name="categoryId" value="{$googleMerchantId|default:''}" required
                       class="form-control" size="10">
            </div>
        </div>

        <div class="row">
            <div class="ml-auto col-sm-6 col-xl-auto">
                <button class="btn btn-primary" type="submit">{__('Re-Generate')}</button>
            </div>
        </div>
    </form>
</div>
<br/>
<div class="second">
    <div class="subheading1">{__('Via category search')}</div>
    <hr class="mb-3">

    <form name="cig_regen_by_search" method="post">
        {$jtl_token}
        <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">

        <div class="form-group form-row align-items-center">
            <label class="col col-sm-4  col-form-label text-sm-right" for="categoryName">{__('Category-Name')}</label>
            <div class="col-sm pr-sm-8 order-last">
                <div class="input-group">
                    <input type="text" name="categoryName" value="{$categoryName|default:''}"
                           class="form-control" size="10">
                    <div class="input-group-append">
                        <button id="search" class="btn btn-secondary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group form-row align-items-center">
            <label class="col col-sm-4  col-form-label text-sm-right"
                   for="categoryId">{__('Select a  category')}</label>
            <div class="col-sm pr-sm-8 order-last">
                <select name="categoryId" class="form-select" style="width: 100%" size="10" required>
                    <option disabled>{__('Search & select a category')}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="ml-auto col-sm-6 col-xl-auto">
                <button class="btn btn-primary" type="submit">{__('Re-Generate')}</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $reGenBySearchForm = $('form[name="cig_regen_by_search"]');
    $reGenBySearchCategorySelect = $reGenBySearchForm.find('select[name="categoryId"]');

    $reGenBySearchForm.find('#search').click(function () {
        let categoryName = $reGenBySearchForm.find('input[name="categoryName"]').val();

        $.post(
            '{$API_URL}',
            {
                jtl_token: '{$smarty.session.jtl_token}',
                categoryName: categoryName
            },
            function (response) {
                $reGenBySearchCategorySelect.empty()

                $.each(response, function (key, value) {
                    $reGenBySearchCategorySelect.append('<option value="' + key + '" selected="selected">' + value + '</option>');
                });
            }
        );

        // TODO: post error case ...
    });

</script>