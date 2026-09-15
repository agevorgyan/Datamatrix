<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Գաղտնաբառի վերականգնում</title>
</head>
<body style="font-family: system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #1e293b; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background-color: #0f172a; padding: 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 800; tracking-wide: 0.05em; color: #38bdf8;">
                🔑 DATAMATRIX ELAB
            </h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; color: #94a3b8;">
                Գաղտնաբառի Վերականգնման Հարցում
            </p>
        </div>

        <!-- Content Body -->
        <div style="padding: 24px;">
            <p style="font-size: 15px; color: #0f172a; font-weight: 700; margin-top: 0;">
                Ողջույն, {{ $user->name }}
            </p>

            <p style="font-size: 14px; color: #334155; leading-relaxed: 1.6;">
                Դուք (կամ ինչ-որ մեկը) հարցում եք ուղարկել <b>datamatrix.elab.am</b> համակարգում Ձեր հաշվի գաղտնաբառը վերականգնելու համար։
            </p>

            <p style="font-size: 14px; color: #334155; margin-bottom: 24px;">
                Նոր գաղտնաբառ սահմանելու համար սեղմեք ստորև նշված կոճակը․
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #0284c7; color: #ffffff; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.3);">
                    🔓 Վերականգնել Գաղտնաբառը
                </a>
            </div>

            <p style="font-size: 12px; color: #64748b; margin-top: 24px;">
                Եթե կոճակը չի աշխատում, պատճենեք հետևյալ հղումը և բացեք դիտարկիչում (Browser)․<br>
                <a href="{{ $resetUrl }}" style="color: #0284c7; word-break: break-all;">{{ $resetUrl }}</a>
            </p>

            <p style="font-size: 12px; color: #94a3b8; margin-top: 20px; border-top: 1px dashed #e2e8f0; padding-top: 12px;">
                ⚠️ Եթե Դուք գաղտնաբառի վերականգնման հարցում չեք ուղարկել, ապա կարող եք անտեսել այս նամակը։ Ձեր գաղտնաբառը կմնա անփոփոխ։
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            Այս նամակը ուղարկվել է ավտոմատ կերպով <a href="https://datamatrix.elab.am" style="color: #64748b;">datamatrix.elab.am</a> համակարգից։
        </div>

    </div>
</body>
</html>
