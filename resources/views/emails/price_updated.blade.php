<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Зміна ціни оголошення OLX</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333333; margin: 0; padding: 0;">
    <div style="max-width: 600px; background: #ffffff; margin: 40px auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
        
        <div style="background: #4CAF50; color: #ffffff; text-align: center; padding: 20px; font-size: 20px; font-weight: bold;">
            🔔 Оновлення ціни на оголошення OLX
        </div>
        
        <div style="padding: 30px;">
            <h1 style="font-size: 22px; margin-bottom: 20px;">Ціна на оголошення змінилася!</h1>

            <p style="font-size: 16px; line-height: 1.6;">Вітаємо!</p>
            <p style="font-size: 16px; line-height: 1.6;">Ціна на оголошення, на яке ви підписані, була оновлена:</p>

            <p style="font-size: 16px; line-height: 1.6;">
                <strong>Посилання на оголошення:</strong><br>
                <a href="{{ $link->url }}" target="_blank" style="color: #4CAF50; text-decoration: none;">{{ $link->url }}</a>
            </p>

            <p style="font-size: 16px; line-height: 1.6;">
                <strong>Нова ціна:</strong> 
                <span style="font-size: 18px; font-weight: bold; color: #4CAF50;">
                    {{ number_format($price->price, 0, '.', ' ') }} {{ $price->currency }}
                </span>
            </p>

            <p style="font-size: 16px; line-height: 1.6;">Щоб переглянути оголошення та дізнатися більше, натисніть кнопку нижче:</p>

            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ $link->url }}" target="_blank" style="display: inline-block; padding: 12px 24px; background: #4CAF50; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 5px;">
                    Перейти до оголошення
                </a>
            </p>

            <p style="font-size: 16px; line-height: 1.6;">Дякуємо, що користуєтесь нашим сервісом відстеження цін на OLX 💚</p>
        </div>

        <div style="background: #f1f1f1; text-align: center; padding: 15px; font-size: 13px; color: #777777;">
            Це автоматичний лист. Будь ласка, не відповідайте на нього.<br>
            © {{ date('Y') }} Ваш сервіс моніторингу OLX
        </div>
    </div>
</body>
</html>
