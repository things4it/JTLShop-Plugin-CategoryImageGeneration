<div class="first">
    <div class="subheading1">{__('admin.regenerate.by-id.title')}</div>
    <hr class="mb-3">

    <form method="post">
        {$jtl_token}
        <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">

        <div class="form-group form-row align-items-center">
            <label class="col col-sm-4  col-form-label text-sm-right"
                   for="googleMerchantId">{__('admin.regenerate.by-id.category-id.label')}</label>
            <div class="col-sm pr-sm-8 order-last">
                <input type="number" name="categoryId" required
                       class="form-control" size="10">
            </div>
        </div>

        <div class="row">
            <div class="ml-auto col-sm-6 col-xl-auto">
                <button class="btn btn-primary" type="submit">{__('admin.regenerate.common.btn-submit')}</button>
            </div>
        </div>
    </form>
</div>
<br/>
<div class="second">
    <div class="subheading1">{__('admin.regenerate.by-name.title')}</div>
    <hr class="mb-3">

    <form name="cig_regen_by_search" method="post">
        {$jtl_token}
        <input type="hidden" name="kPluginAdminMenu" value="{$menuID}">

        <div class="form-group form-row align-items-center">
            <label class="col col-sm-4  col-form-label text-sm-right"
                   for="categoryName">{__('admin.regenerate.by-name.category-name')}</label>
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
                   for="categoryId">{__('admin.regenerate.by-name.select-category.label')}</label>
            <div class="col-sm pr-sm-8 order-last">
                <select name="categoryId" class="form-select" style="width: 100%" size="10" required>
                    <option disabled>{__('admin.regenerate.by-name.select-category.disabled-option')}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="ml-auto col-sm-6 col-xl-auto">
                <button class="btn btn-primary" type="submit">{__('admin.regenerate.common.btn-submit')}</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    let $reGenBySearchForm = $('form[name="cig_regen_by_search"]');
    let $reGenBySearchCategoryNameInput = $reGenBySearchForm.find('input[name="categoryName"]');
    let $reGenBySearchCategorySelect = $reGenBySearchForm.find('select[name="categoryId"]');

    function executeSearch() {
        $reGenBySearchCategorySelect.prop('disabled', true);

        let categoryName = $reGenBySearchCategoryNameInput.val();

        $.post('{$API_URL}', {
            jtl_token: '{$smarty.session.jtl_token}',
            categoryName: categoryName

        }).done((response) => {
            $reGenBySearchCategorySelect.empty()

            $.each(response, function (key, value) {
                $reGenBySearchCategorySelect.append('<option value="' + key + '" selected="selected">' + value + '</option>');
            });
        }).fail((error) => {
            alert(error.responseJSON.message);
        }).always(() => {
            $reGenBySearchCategorySelect.prop('disabled', false);
        });
    };

    $(document).ready(() => {
        $reGenBySearchForm.find('#search').on('click', () => executeSearch());
        $reGenBySearchCategoryNameInput.on('keydown', (event) => {
            if (event.key === 'Enter' || event.keyCode === 13) {
                executeSearch();

                event.preventDefault();
                return false;
            }
        });
    });

</script>