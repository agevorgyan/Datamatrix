<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Նոր օգտվողի գրանցում</title>
</head>
<body style="font-family: system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #1e293b; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background-color: #0f172a; padding: 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 800; tracking-wide: 0.05em; color: #38bdf8;">
                🏷️ DATAMATRIX ELAB
            </h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; color: #94a3b8;">
                Նոր Օգտվողի Գրանցման Ծանուցում
            </p>
        </div>

        <!-- Content Body -->
        <div style="padding: 24px;">
            <h2 style="font-size: 16px; margin-top: 0; margin-bottom: 16px; color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                👤 Գրանցվողի Ամբողջական Տվյալները
            </h2>

            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600; width: 40%;">Անուն Ազգանուն:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Էլ. Հասցե:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0284c7; font-weight: 700;">
                        <a href="mailto:{{ $user->email }}" style="color: #0284c7; text-decoration: none;">{{ $user->email }}</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Հեռախոսահամար:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">{{ $user->phone ?? 'Մուտքագրված չէ' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-weight: 600;">Մարքեթինգի Համաձայնություն:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        @if($user->marketing_consent)
                            <span style="display: inline-block; background-color: #dcfce7; color: #166534; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 12px;">
                                ✅ Համաձայն է (Այո)
                            </span>
                        @else
                            <span style="display: inline-block; background-color: #f1f5f9; color: #64748b; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-size: 12px;">
                                ❌ Համաձայն չէ (Ոչ)
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Գրանցման Ամսաթիվ:</td>
                    <td style="padding: 10px 0; color: #0f172a; font-weight: 600;">{{ $user->created_at ? $user->created_at->format('d.m.Y H:i:s') : date('d.m.Y H:i:s') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 16px 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            Այս նամակը ուղարկվել է ավտոմատ կերպով <a href="https://datamatrix.elab.am" style="color: #64748b;">datamatrix.elab.am</a> համակարգից։
        </div>

    </div>
</body>
</html>
