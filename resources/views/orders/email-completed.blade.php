@component('mail::message')

при установке статуса заказа "завершен" требуется отправить email - партнеру и всем поставщикам продуктов из заказа

    заказ №(номер) завершен
    текст состав заказа (список), стоимость заказа (значение)


# Заказ № завершен.

* Товар , на сумму .

Thanks,<br>
{{ config('app.name') }}
@endcomponent
