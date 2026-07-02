<?php

/**
 * CMS overlay translations for de, es, fr, it, pt, tr, ru.
 * English and Arabic are seeded by ContentSeeder.
 */

$loc = ['de', 'es', 'fr', 'it', 'pt', 'tr', 'ru'];

$map = static function (array $byLocale): array {
    return $byLocale;
};

$label = static function (string $de, string $es, string $fr, string $it, string $pt, string $tr, string $ru): array {
    return [
        'de' => ['label' => $de],
        'es' => ['label' => $es],
        'fr' => ['label' => $fr],
        'it' => ['label' => $it],
        'pt' => ['label' => $pt],
        'tr' => ['label' => $tr],
        'ru' => ['label' => $ru],
    ];
};

$qa = static function (
    string $deQ, string $deA,
    string $esQ, string $esA,
    string $frQ, string $frA,
    string $itQ, string $itA,
    string $ptQ, string $ptA,
    string $trQ, string $trA,
    string $ruQ, string $ruA,
): array {
    return [
        'de' => ['question' => $deQ, 'answer' => $deA],
        'es' => ['question' => $esQ, 'answer' => $esA],
        'fr' => ['question' => $frQ, 'answer' => $frA],
        'it' => ['question' => $itQ, 'answer' => $itA],
        'pt' => ['question' => $ptQ, 'answer' => $ptA],
        'tr' => ['question' => $trQ, 'answer' => $trA],
        'ru' => ['question' => $ruQ, 'answer' => $ruA],
    ];
};

$td = static function (
    string $deT, string $deD,
    string $esT, string $esD,
    string $frT, string $frD,
    string $itT, string $itD,
    string $ptT, string $ptD,
    string $trT, string $trD,
    string $ruT, string $ruD,
): array {
    return [
        'de' => ['title' => $deT, 'description' => $deD],
        'es' => ['title' => $esT, 'description' => $esD],
        'fr' => ['title' => $frT, 'description' => $frD],
        'it' => ['title' => $itT, 'description' => $itD],
        'pt' => ['title' => $ptT, 'description' => $ptD],
        'tr' => ['title' => $trT, 'description' => $trD],
        'ru' => ['title' => $ruT, 'description' => $ruD],
    ];
};

return [
    'faqs' => [
        1 => $qa(
            'Wie lange dauert die Entwicklung einer Markenidentität?',
            'In der Regel 10–14 Werktage, einschließlich Recherche, Entwürfen und Überarbeitungen.',
            '¿Cuánto tiempo lleva una identidad de marca?',
            'Normalmente de 10 a 14 días hábiles, incluyendo investigación, borradores y revisiones.',
            'Combien de temps faut-il pour une identité de marque ?',
            'Généralement 10 à 14 jours ouvrables, recherche, maquettes et révisions incluses.',
            'Quanto tempo richiede un\'identità di marca?',
            'Di solito 10-14 giorni lavorativi, inclusi ricerca, bozze e revisioni.',
            'Quanto tempo leva uma identidade de marca?',
            'Normalmente 10 a 14 dias úteis, incluindo pesquisa, rascunhos e revisões.',
            'Bir marka kimliği ne kadar sürer?',
            'Genellikle araştırma, taslaklar ve revizyonlar dahil 10-14 iş günü.',
            'Сколько времени занимает создание фирменного стиля?',
            'Обычно 10–14 рабочих дней, включая исследование, черновики и правки.'
        ),
        2 => $qa(
            'Arbeiten Sie mit kleinen Unternehmen?',
            'Ja, wir haben maßgeschneiderte Pakete für Unternehmen jeder Größe – von Start-ups bis zu Konzernen.',
            '¿Trabajan con pequeñas empresas?',
            'Sí, tenemos paquetes adaptados para negocios de todos los tamaños, desde startups hasta corporaciones.',
            'Travaillez-vous avec les petites entreprises ?',
            'Oui, nous proposons des offres adaptées à toutes les tailles, des startups aux grands groupes.',
            'Lavorate con piccole imprese?',
            'Sì, abbiamo pacchetti su misura per realtà di ogni dimensione, dalle startup alle aziende corporate.',
            'Trabalham com pequenas empresas?',
            'Sim, temos pacotes adaptados para negócios de todos os tamanhos, de startups a corporações.',
            'Küçük işletmelerle çalışıyor musunuz?',
            'Evet, girişimlerden kurumsal şirketlere kadar her ölçek için uygun paketlerimiz var.',
            'Работаете ли вы с малым бизнесом?',
            'Да, у нас есть пакеты для компаний любого масштаба — от стартапов до корпораций.'
        ),
        3 => $qa(
            'Wie sind die Zahlungsbedingungen?',
            'Wir akzeptieren Banküberweisungen und gängige Karten. Üblicherweise 50 % im Voraus und 50 % bei Lieferung.',
            '¿Cuáles son las condiciones de pago?',
            'Aceptamos transferencias bancarias y tarjetas principales. Normalmente 50 % por adelantado y 50 % al entregar.',
            'Quelles sont les conditions de paiement ?',
            'Nous acceptons virements bancaires et cartes principales. En général 50 % à l\'avance et 50 % à la livraison.',
            'Quali sono i termini di pagamento?',
            'Accettiamo bonifici e carte principali. Di solito 50% anticipo e 50% alla consegna.',
            'Quais são os termos de pagamento?',
            'Aceitamos transferências bancárias e cartões principais. Normalmente 50% adiantado e 50% na entrega.',
            'Ödeme koşulları nelerdir?',
            'Banka havalesi ve başlıca kartları kabul ediyoruz. Genellikle %50 peşin, %50 teslimatta.',
            'Какие условия оплаты?',
            'Принимаем банковские переводы и основные карты. Обычно 50% аванс и 50% при сдаче.'
        ),
        4 => $qa(
            'Stellen Sie Kampagnenberichte bereit?',
            'Ja. Wir liefern detaillierte wöchentliche und monatliche Berichte mit allen wichtigen KPIs.',
            '¿Proporcionan informes de campaña?',
            'Por supuesto. Entregamos informes semanales y mensuales detallados con todos los KPIs clave.',
            'Fournissez-vous des rapports de campagne ?',
            'Absolument. Nous fournissons des rapports hebdomadaires et mensuels détaillés avec tous les KPI clés.',
            'Fornite report sulle campagne?',
            'Certamente. Forniamo report settimanali e mensili dettagliati con tutti i KPI principali.',
            'Fornecem relatórios de campanha?',
            'Com certeza. Entregamos relatórios semanais e mensais detalhados com todos os KPIs principais.',
            'Kampanya raporları sağlıyor musunuz?',
            'Kesinlikle. Tüm önemli KPI\'ları izleyen ayrıntılı haftalık ve aylık raporlar sunuyoruz.',
            'Предоставляете ли отчёты по кампаниям?',
            'Конечно. Мы готовим подробные еженедельные и ежемесячные отчёты по всем ключевым KPI.'
        ),
        5 => $qa(
            'Kann ich meinen Plan später upgraden oder downgraden?',
            'Ja, unsere Pläne sind flexibel und können jederzeit an Ihre Geschäftsanforderungen angepasst werden.',
            '¿Puedo mejorar o reducir mi plan más adelante?',
            'Sí, nuestros planes son flexibles y pueden adaptarse en cualquier momento según sus necesidades.',
            'Puis-je modifier mon forfait plus tard ?',
            'Oui, nos offres sont flexibles et peuvent être adaptées à tout moment selon vos besoins.',
            'Posso aggiornare o ridurre il mio piano in seguito?',
            'Sì, i nostri piani sono flessibili e possono essere adattati in qualsiasi momento.',
            'Posso fazer upgrade ou downgrade do meu plano depois?',
            'Sim, nossos planos são flexíveis e podem ser adaptados a qualquer momento conforme sua necessidade.',
            'Planımı daha sonra yükseltebilir veya düşürebilir miyim?',
            'Evet, planlarımız esnektir ve iş ihtiyaçlarınıza göre istediğiniz zaman uyarlanabilir.',
            'Можно ли позже изменить тарифный план?',
            'Да, наши планы гибкие и могут быть изменены в любой момент под ваши задачи.'
        ),
    ],

    'timeline' => [
        1 => $td(
            'Studio gegründet', 'Als Boutique-Film- und Videoproduktionsstudio mit Vision für cineastisches Storytelling gestartet',
            'Estudio fundado', 'Lanzado como estudio boutique de cine y video con visión de narrativa cinematográfica',
            'Studio fondé', 'Lancé comme studio boutique de film et vidéo avec une vision du storytelling cinématographique',
            'Studio fondato', 'Avviato come boutique di produzione cinematografica e video con visione narrativa',
            'Estúdio fundado', 'Lançado como estúdio boutique de cinema e vídeo com visão de storytelling cinematográfico',
            'Stüdyo kuruldu', 'Sinematik hikâye anlatımı vizyonuyla butik film ve video prodüksiyon stüdyosu olarak başladı',
            'Основание студии', 'Запуск как бутик-студии кино и видео с видением кинематографического сторителлинга'
        ),
        2 => $td(
            'MENA-Expansion', 'Büros in der MENA-Region eröffnet, um Marken, Plattformen und Sender zu bedienen',
            'Expansión MENA', 'Oficinas abiertas en la región MENA para atender marcas, plataformas y emisoras',
            'Expansion MENA', 'Ouverture de bureaux dans la région MENA pour servir marques, plateformes et diffuseurs',
            'Espansione MENA', 'Aperti uffici nella regione MENA per servire brand, piattaforme e broadcaster',
            'Expansão MENA', 'Escritórios abertos na região MENA para atender marcas, plataformas e emissoras',
            'MENA genişlemesi', 'Markalar, platformlar ve yayıncılara hizmet vermek için MENA bölgesinde ofisler açıldı',
            'Расширение в MENA', 'Открыли офисы в регионе MENA для работы с брендами, платформами и телеканалами'
        ),
        3 => $td(
            '50+ Kunden', 'Mehr als 50 zufriedene Kunden mit wachsender Wiederbeauftragungsrate erreicht',
            '50+ clientes', 'Más de 50 clientes satisfechos con una creciente tasa de repetición',
            '50+ clients', 'Plus de 50 clients satisfaits avec un taux de réachat en hausse',
            '50+ clienti', 'Oltre 50 clienti soddisfatti con un tasso di riordino in crescita',
            '50+ clientes', 'Mais de 50 clientes satisfeitos com taxa crescente de retorno',
            '50+ müşteri', 'Artan tekrar iş oranıyla 50\'den fazla memnun müşteriye ulaşıldı',
            '50+ клиентов', 'Более 50 довольных клиентов с растущим процентом повторных заказов'
        ),
        4 => $td(
            'Produktionshub Ägypten', 'Vollständige On-the-Ground-Produktionsunterstützung für internationale Dreharbeiten in Ägypten',
            'Centro de producción en Egipto', 'Soporte completo de producción in situ para rodajes internacionales en Egipto',
            'Hub de production en Égypte', 'Support de production complet sur le terrain pour tournages internationaux en Égypte',
            'Hub produttivo in Egitto', 'Supporto produttivo completo on-the-ground per riprese internazionali in Egitto',
            'Hub de produção no Egito', 'Suporte completo de produção in loco para filmagens internacionais no Egito',
            'Mısır prodüksiyon merkezi', 'Mısır\'daki uluslararası çekimler için tam yerinde prodüksiyon desteği kuruldu',
            'Производственный хаб в Египте', 'Полная наземная продакшн-поддержка для международных съёмок в Египте'
        ),
        5 => $td(
            'Originale Formate', 'Expansion in originale Geschichten und Formate für globale Zielgruppen',
            'Formatos originales', 'Expansión hacia historias y formatos originales diseñados para inspirar audiencias globales',
            'Formats originaux', 'Expansion vers des histoires et formats originaux conçus pour un public mondial',
            'Formati originali', 'Espansione in storie e formati originali pensati per ispirare pubblici globali',
            'Formatos originais', 'Expansão para histórias e formatos originais pensados para audiências globais',
            'Orijinal formatlar', 'Küresel izleyicileri ilham verecek orijinal hikâye ve formatlara genişleme',
            'Оригинальные форматы', 'Развитие оригинальных историй и форматов для глобальной аудитории'
        ),
    ],

    'skill_bars' => [
        1 => $label('Werbefilmproduktion', 'Producción comercial', 'Production commerciale', 'Produzione commerciale', 'Produção comercial', 'Ticari prodüksiyon', 'Коммерческое производство'),
        2 => $label('Dokumentarfilme & Markenfilme', 'Documentales y films de marca', 'Documentaires et films de marque', 'Documentari e film di brand', 'Documentários e filmes de marca', 'Belgeseller ve marka filmleri', 'Документальные и брендовые фильмы'),
        3 => $label('Live-Events & Podcasts', 'Eventos en vivo y podcasts', 'Événements live et podcasts', 'Eventi live e podcast', 'Eventos ao vivo e podcasts', 'Canlı etkinlikler ve podcastler', 'Live-события и подкасты'),
        4 => $label('Motion/CGI & Fotografie', 'Motion/CGI y fotografía', 'Motion/CGI et photographie', 'Motion/CGI e fotografia', 'Motion/CGI e fotografia', 'Motion/CGI ve fotoğrafçılık', 'Motion/CGI и фотография'),
        5 => $label('Mehrsprachige Produktion', 'Producción multilingüe', 'Production multilingue', 'Produzione multilingue', 'Produção multilíngue', 'Çok dilli prodüksiyon', 'Мультиязычное производство'),
        6 => $label('Produktionsunterstützung in Ägypten', 'Soporte de producción en Egipto', 'Support de production en Égypte', 'Supporto produttivo in Egitto', 'Suporte de produção no Egito', 'Mısır yerinde prodüksiyon desteği', 'Наземная поддержка в Египте'),
    ],

    'value_items' => [
        1 => $td(
            'Kompletter Produktionszyklus', 'Vom Konzept bis zum finalen Frame – jede Phase mit Präzision und Stil.',
            'Ciclo de producción completo', 'Del concepto al fotograma final, gestionamos cada etapa con precisión.',
            'Cycle de production complet', 'Du concept à l\'image finale, nous maîtrisons chaque étape avec précision.',
            'Ciclo produttivo completo', 'Dal concept al fotogramma finale, gestiamo ogni fase con precisione.',
            'Ciclo de produção completo', 'Do conceito ao frame final, cuidamos de cada etapa com precisão.',
            'Tam prodüksiyon döngüsü', 'Konseptten son kareye kadar her aşamayı hassasiyetle yönetiyoruz.',
            'Полный цикл производства', 'От концепции до финального кадра — каждый этап с точностью и стилем.'
        ),
        2 => $td(
            'Mehrsprachige Produktion', 'Globale Produktion über Sprachen und Märkte hinweg – wir haben alles abgedeckt.',
            'Producción multilingüe', 'Producción global en varios idiomas y mercados: lo tenemos cubierto.',
            'Production multilingue', 'Production globale dans plusieurs langues et marchés — nous couvrons tout.',
            'Produzione multilingue', 'Produzione globale in più lingue e mercati: siamo pronti ovunque.',
            'Produção multilíngue', 'Produção global em vários idiomas e mercados — cobrimos tudo.',
            'Çok dilli prodüksiyon', 'Diller ve pazarlar arasında küresel prodüksiyon — her şeyi karşılıyoruz.',
            'Мультиязычное производство', 'Глобальное производство на разных языках и рынках — мы всё закрываем.'
        ),
        3 => $td(
            'Produktionshub Ägypten', 'Genehmigungen, Crews, Logistik, Locations und volle Unterstützung vor Ort.',
            'Centro de producción en Egipto', 'Permisos, equipos, logística, locaciones y soporte completo in situ.',
            'Hub de production en Égypte', 'Permis, équipes, logistique, lieux et support complet sur le terrain.',
            'Hub produttivo in Egitto', 'Permessi, crew, logistica, location e supporto completo on-the-ground.',
            'Hub de produção no Egito', 'Permissões, equipes, logística, locações e suporte completo in loco.',
            'Mısır prodüksiyon merkezi', 'İzinler, ekip, lojistik, lokasyonlar ve tam yerinde destek.',
            'Производственный хаб в Египте', 'Разрешения, команды, логистика, локации и полная наземная поддержка.'
        ),
        4 => $td(
            'Originales Storytelling', 'Über Kundenarbeit hinaus entwickeln wir Formate, die reisen und inspirieren.',
            'Narrativa original', 'Más allá del trabajo para clientes, desarrollamos formatos que viajan e inspiran.',
            'Storytelling original', 'Au-delà des missions clients, nous créons des formats faits pour voyager et inspirer.',
            'Storytelling originale', 'Oltre il lavoro per i clienti, sviluppiamo formati pensati per viaggiare e ispirare.',
            'Storytelling original', 'Além do trabalho para clientes, desenvolvemos formatos feitos para inspirar.',
            'Orijinal hikâye anlatımı', 'Müşteri işlerinin ötesinde, yolculuk edecek formatlar geliştiriyoruz.',
            'Оригинальный сторителлинг', 'Помимо клиентских проектов создаём форматы, которые вдохновляют по всему миру.'
        ),
    ],

    'feature_highlights' => [
        'services' => [
            1 => $td(
                'Kompletter Produktionszyklus', 'Von Planung und Dreh bis Postproduktion, Lokalisierung und finaler Lieferung.',
                'Ciclo de producción completo', 'Desde la planificación y rodaje hasta postproducción, localización y entrega final.',
                'Cycle de production complet', 'De la planification au tournage, puis post-production, localisation et livraison.',
                'Ciclo produttivo completo', 'Dalla pianificazione alle riprese, post-produzione, localizzazione e consegna.',
                'Ciclo de produção completo', 'Do planeamento e filmagem à pós-produção, localização e entrega final.',
                'Tam prodüksiyon döngüsü', 'Planlamadan çekime, post prodüksiyondan lokalizasyona ve teslimata kadar.',
                'Полный цикл производства', 'От планирования и съёмок до постпродакшна, локализации и финальной сдачи.'
            ),
            2 => $td(
                'MENA & globale Reichweite', 'Büros in Ägypten, Dubai und Dschidda für Kunden weltweit.',
                'Alcance MENA y global', 'Oficinas en Egipto, Dubái y Yeda atendiendo clientes en todo el mundo.',
                'Portée MENA et mondiale', 'Bureaux en Égypte, Dubaï et Djeddah au service de clients du monde entier.',
                'Portata MENA e globale', 'Uffici in Egitto, Dubai e Jeddah al servizio di clienti in tutto il mondo.',
                'Alcance MENA e global', 'Escritórios no Egito, Dubai e Jeddah atendendo clientes no mundo todo.',
                'MENA ve küresel erişim', 'Mısır, Dubai ve Cidde ofisleriyle dünya çapında hizmet.',
                'MENA и глобальный охват', 'Офисы в Египте, Дубае и Джидде для клиентов по всему миру.'
            ),
            3 => $td(
                'Vor Ort in Ägypten', 'Genehmigungen, Crews, Equipment, Logistik und Locations für internationale Drehs.',
                'Producción in situ en Egipto', 'Permisos, equipos, gear, logística y locaciones para rodajes internacionales.',
                'Sur le terrain en Égypte', 'Permis, équipes, matériel, logistique et lieux pour tournages internationaux.',
                'On-the-ground in Egitto', 'Permessi, crew, attrezzatura, logistica e location per riprese internazionali.',
                'Produção in loco no Egito', 'Permissões, equipes, equipamentos, logística e locações para filmagens internacionais.',
                'Mısır\'da yerinde prodüksiyon', 'Uluslararası çekimler için izinler, ekip, ekipman, lojistik ve lokasyonlar.',
                'Наземная работа в Египте', 'Разрешения, команды, техника, логистика и локации для международных съёмок.'
            ),
            4 => $td(
                'Disziplinierte Umsetzung', 'Planbare Budgets, Premium-Ergebnisse und klare nächste Schritte.',
                'Ejecución disciplinada', 'Presupuestos predecibles, resultados premium y próximos pasos claros.',
                'Exécution disciplinée', 'Budgets prévisibles, résultats premium et prochaines étapes claires.',
                'Esecuzione disciplinata', 'Budget prevedibili, risultati premium e passi successivi chiari.',
                'Execução disciplinada', 'Orçamentos previsíveis, resultados premium e próximos passos claros.',
                'Disiplinli uygulama', 'Öngörülebilir bütçeler, üst düzey sonuçlar ve net sonraki adımlar.',
                'Дисциплинированное исполнение', 'Предсказуемые бюджеты, премиальный результат и понятные следующие шаги.'
            ),
        ],
        'contact' => [
            1 => $td(
                'Antwort in 1 Stunde', 'Unser Team antwortet an Werktagen innerhalb einer Stunde auf Ihre Anfragen.',
                'Respuesta en 1 hora', 'Nuestro equipo responde sus consultas en una hora en días laborables.',
                'Réponse en 1 heure', 'Notre équipe répond à vos demandes en une heure les jours ouvrables.',
                'Risposta in 1 ora', 'Il nostro team risponde alle richieste entro un\'ora nei giorni lavorativi.',
                'Resposta em 1 hora', 'Nossa equipe responde às suas consultas em uma hora em dias úteis.',
                '1 saat içinde yanıt', 'Ekibimiz iş günlerinde sorularınıza bir saat içinde yanıt verir.',
                'Ответ за 1 час', 'Наша команда отвечает на запросы в течение часа в рабочие дни.'
            ),
            2 => $td(
                'Kostenlose Beratung', 'Erhalten Sie eine kostenlose 30-minütige Beratung mit einem unserer Experten.',
                'Consulta gratuita', 'Obtenga una consulta gratuita de 30 minutos con uno de nuestros expertos.',
                'Consultation gratuite', 'Obtenez une consultation gratuite de 30 minutes avec l\'un de nos experts.',
                'Consulenza gratuita', 'Ricevi una consulenza gratuita di 30 minuti con uno dei nostri esperti.',
                'Consultoria gratuita', 'Receba uma consultoria gratuita de 30 minutos com um de nossos especialistas.',
                'Ücretsiz danışmanlık', 'Uzmanlarımızdan 30 dakikalık ücretsiz danışmanlık alın.',
                'Бесплатная консультация', 'Получите бесплатную 30-минутную консультацию с нашим экспертом.'
            ),
            3 => $td(
                'Volle Vertraulichkeit', 'Ihre Informationen und Ihr Projekt sind nach höchsten Datenschutzstandards geschützt.',
                'Confidencialidad total', 'Su información y proyecto están totalmente protegidos bajo los más altos estándares.',
                'Confidentialité totale', 'Vos informations et votre projet sont protégés selon les normes les plus strictes.',
                'Piena riservatezza', 'Le tue informazioni e il tuo progetto sono protetti secondo i massimi standard.',
                'Confidencialidade total', 'Suas informações e projeto estão totalmente protegidos pelos mais altos padrões.',
                'Tam gizlilik', 'Bilgileriniz ve projeniz en yüksek gizlilik standartlarıyla korunur.',
                'Полная конфиденциальность', 'Ваша информация и проект защищены по самым высоким стандартам.'
            ),
            4 => $td(
                '9+ Jahre Erfahrung', 'Ein Team von Spezialisten in allen Bereichen des digitalen Marketings.',
                '9+ años de experiencia', 'Un equipo de especialistas en todas las áreas del marketing digital.',
                '9+ ans d\'expérience', 'Une équipe de spécialistes dans tous les domaines du marketing digital.',
                '9+ anni di esperienza', 'Un team di specialisti in tutte le aree del marketing digitale.',
                '9+ anos de experiência', 'Uma equipe de especialistas em todas as áreas do marketing digital.',
                '9+ yıllık deneyim', 'Dijital pazarlamanın tüm alanlarında uzman bir ekip.',
                '9+ лет опыта', 'Команда специалистов во всех областях цифрового маркетинга.'
            ),
        ],
    ],

    'partner_labels' => [
        1 => $label('Marken', 'Marcas', 'Marques', 'Brand', 'Marcas', 'Markalar', 'Бренды'),
        2 => $label('Plattformen', 'Plataformas', 'Plateformes', 'Piattaforme', 'Plataformas', 'Platformlar', 'Платформы'),
        3 => $label('Sender', 'Emisoras', 'Diffuseurs', 'Broadcaster', 'Emissoras', 'Yayıncılar', 'Телеканалы'),
        4 => $label('Institutionen', 'Instituciones', 'Institutions', 'Istituzioni', 'Instituições', 'Kurumlar', 'Институции'),
        5 => $label('Internationale Produktion', 'Producción internacional', 'Production internationale', 'Produzione internazionale', 'Produção internacional', 'Uluslararası prodüksiyon', 'Международное производство'),
    ],

    'awards' => [
        1 => $map([
            'de' => ['title' => 'Film & Video', 'organization' => 'Kompletter Produktionszyklus', 'year_label' => 'MENA'],
            'es' => ['title' => 'Cine y video', 'organization' => 'Ciclo de producción completo', 'year_label' => 'MENA'],
            'fr' => ['title' => 'Film & vidéo', 'organization' => 'Cycle de production complet', 'year_label' => 'MENA'],
            'it' => ['title' => 'Film e video', 'organization' => 'Ciclo produttivo completo', 'year_label' => 'MENA'],
            'pt' => ['title' => 'Cinema e vídeo', 'organization' => 'Ciclo de produção completo', 'year_label' => 'MENA'],
            'tr' => ['title' => 'Film ve video', 'organization' => 'Tam prodüksiyon döngüsü', 'year_label' => 'MENA'],
            'ru' => ['title' => 'Кино и видео', 'organization' => 'Полный цикл производства', 'year_label' => 'MENA'],
        ]),
        2 => $map([
            'de' => ['title' => 'Werbefilmproduktion', 'organization' => 'Werbung & Markencontent', 'year_label' => 'Global'],
            'es' => ['title' => 'Producción comercial', 'organization' => 'Publicidad y contenido de marca', 'year_label' => 'Global'],
            'fr' => ['title' => 'Production commerciale', 'organization' => 'Publicité et contenu de marque', 'year_label' => 'Global'],
            'it' => ['title' => 'Produzione commerciale', 'organization' => 'Pubblicità e contenuti di brand', 'year_label' => 'Global'],
            'pt' => ['title' => 'Produção comercial', 'organization' => 'Publicidade e conteúdo de marca', 'year_label' => 'Global'],
            'tr' => ['title' => 'Ticari prodüksiyon', 'organization' => 'Reklam ve marka içeriği', 'year_label' => 'Global'],
            'ru' => ['title' => 'Коммерческое производство', 'organization' => 'Реклама и брендовый контент', 'year_label' => 'Global'],
        ]),
        3 => $map([
            'de' => ['title' => 'Produktion vor Ort', 'organization' => 'Ägypten, Dubai & Dschidda', 'year_label' => '3+ Büros'],
            'es' => ['title' => 'Producción in situ', 'organization' => 'Egipto, Dubái y Yeda', 'year_label' => '3+ oficinas'],
            'fr' => ['title' => 'Production sur le terrain', 'organization' => 'Égypte, Dubaï et Djeddah', 'year_label' => '3+ bureaux'],
            'it' => ['title' => 'Produzione on-the-ground', 'organization' => 'Egitto, Dubai e Jeddah', 'year_label' => '3+ uffici'],
            'pt' => ['title' => 'Produção in loco', 'organization' => 'Egito, Dubai e Jeddah', 'year_label' => '3+ escritórios'],
            'tr' => ['title' => 'Yerinde prodüksiyon', 'organization' => 'Mısır, Dubai ve Cidde', 'year_label' => '3+ ofis'],
            'ru' => ['title' => 'Наземное производство', 'organization' => 'Египет, Дубай и Джидда', 'year_label' => '3+ офиса'],
        ]),
        4 => $map([
            'de' => ['title' => 'Originale IP', 'organization' => 'Geschichten, die reisen', 'year_label' => 'In-house'],
            'es' => ['title' => 'IP original', 'organization' => 'Historias hechas para viajar', 'year_label' => 'Interno'],
            'fr' => ['title' => 'IP originale', 'organization' => 'Des histoires faites pour voyager', 'year_label' => 'Interne'],
            'it' => ['title' => 'IP originale', 'organization' => 'Storie pensate per viaggiare', 'year_label' => 'In-house'],
            'pt' => ['title' => 'IP original', 'organization' => 'Histórias feitas para viajar', 'year_label' => 'Interno'],
            'tr' => ['title' => 'Orijinal IP', 'organization' => 'Yolculuk edecek hikâyeler', 'year_label' => 'Dahili'],
            'ru' => ['title' => 'Оригинальный IP', 'organization' => 'Истории, созданные путешествовать', 'year_label' => 'In-house'],
        ]),
    ],

    'offices' => [
        1 => $map([
            'de' => ['title' => 'Büro Ägypten', 'status' => 'Hauptbüro'],
            'es' => ['title' => 'Oficina Egipto', 'status' => 'Oficina principal'],
            'fr' => ['title' => 'Bureau Égypte', 'status' => 'Siège principal'],
            'it' => ['title' => 'Ufficio Egitto', 'status' => 'Sede principale'],
            'pt' => ['title' => 'Escritório Egito', 'status' => 'Sede principal'],
            'tr' => ['title' => 'Mısır Ofisi', 'status' => 'Ana Ofis'],
            'ru' => ['title' => 'Офис в Египте', 'status' => 'Главный офис'],
        ]),
        2 => $map([
            'de' => ['title' => 'Büro VAE', 'status' => 'Regionalbüro'],
            'es' => ['title' => 'Oficina EAU', 'status' => 'Oficina regional'],
            'fr' => ['title' => 'Bureau EAU', 'status' => 'Bureau régional'],
            'it' => ['title' => 'Ufficio EAU', 'status' => 'Ufficio regionale'],
            'pt' => ['title' => 'Escritório EAU', 'status' => 'Escritório regional'],
            'tr' => ['title' => 'BAE Ofisi', 'status' => 'Bölgesel Ofis'],
            'ru' => ['title' => 'Офис в ОАЭ', 'status' => 'Региональный офис'],
        ]),
    ],

    'resources' => [
        1 => $map([
            'de' => ['title' => 'Digital-Marketing-Leitfaden 2024', 'type_label' => 'Kostenloses PDF'],
            'es' => ['title' => 'Guía de marketing digital 2024', 'type_label' => 'PDF gratuito'],
            'fr' => ['title' => 'Guide du marketing digital 2024', 'type_label' => 'PDF gratuit'],
            'it' => ['title' => 'Guida al marketing digitale 2024', 'type_label' => 'PDF gratuito'],
            'pt' => ['title' => 'Guia de marketing digital 2024', 'type_label' => 'PDF gratuito'],
            'tr' => ['title' => 'Dijital Pazarlama Rehberi 2024', 'type_label' => 'Ücretsiz PDF'],
            'ru' => ['title' => 'Гид по digital-маркетингу 2024', 'type_label' => 'Бесплатный PDF'],
        ]),
        2 => $map([
            'de' => ['title' => 'Monatliche Performance-Berichtsvorlage', 'type_label' => 'Kostenloses Excel'],
            'es' => ['title' => 'Plantilla de informe mensual de rendimiento', 'type_label' => 'Excel gratuito'],
            'fr' => ['title' => 'Modèle de rapport de performance mensuel', 'type_label' => 'Excel gratuit'],
            'it' => ['title' => 'Modello report mensile delle performance', 'type_label' => 'Excel gratuito'],
            'pt' => ['title' => 'Modelo de relatório mensal de desempenho', 'type_label' => 'Excel gratuito'],
            'tr' => ['title' => 'Aylık Performans Raporu Şablonu', 'type_label' => 'Ücretsiz Excel'],
            'ru' => ['title' => 'Шаблон ежемесячного отчёта по эффективности', 'type_label' => 'Бесплатный Excel'],
        ]),
        3 => $map([
            'de' => ['title' => 'Glossar wichtiger Marketingbegriffe', 'type_label' => 'Kostenloses Lexikon'],
            'es' => ['title' => 'Glosario de términos esenciales de marketing', 'type_label' => 'Diccionario gratuito'],
            'fr' => ['title' => 'Glossaire des termes marketing essentiels', 'type_label' => 'Dictionnaire gratuit'],
            'it' => ['title' => 'Glossario dei termini marketing essenziali', 'type_label' => 'Dizionario gratuito'],
            'pt' => ['title' => 'Glossário de termos essenciais de marketing', 'type_label' => 'Dicionário gratuito'],
            'tr' => ['title' => 'Temel Pazarlama Terimleri Sözlüğü', 'type_label' => 'Ücretsiz sözlük'],
            'ru' => ['title' => 'Глоссарий ключевых маркетинговых терминов', 'type_label' => 'Бесплатный словарь'],
        ]),
        4 => $map([
            'de' => ['title' => 'Social-Media-Workshop', 'type_label' => 'Kostenloses Video'],
            'es' => ['title' => 'Taller de redes sociales', 'type_label' => 'Video gratuito'],
            'fr' => ['title' => 'Atelier réseaux sociaux', 'type_label' => 'Vidéo gratuite'],
            'it' => ['title' => 'Workshop sui social media', 'type_label' => 'Video gratuito'],
            'pt' => ['title' => 'Workshop de redes sociais', 'type_label' => 'Vídeo gratuito'],
            'tr' => ['title' => 'Sosyal Medya Atölyesi', 'type_label' => 'Ücretsiz video'],
            'ru' => ['title' => 'Воркшоп по соцсетям', 'type_label' => 'Бесплатное видео'],
        ]),
    ],

    'categories' => [
        'portfolio' => [
            'digital-ads' => $map([
                'de' => ['name' => 'Digitale Anzeigen', 'label' => '📊 Digitale Anzeigen'],
                'es' => ['name' => 'Anuncios digitales', 'label' => '📊 Anuncios digitales'],
                'fr' => ['name' => 'Publicités digitales', 'label' => '📊 Publicités digitales'],
                'it' => ['name' => 'Annunci digitali', 'label' => '📊 Annunci digitali'],
                'pt' => ['name' => 'Anúncios digitais', 'label' => '📊 Anúncios digitais'],
                'tr' => ['name' => 'Dijital reklamlar', 'label' => '📊 Dijital reklamlar'],
                'ru' => ['name' => 'Цифровая реклама', 'label' => '📊 Цифровая реклама'],
            ]),
            'branding' => $map([
                'de' => ['name' => 'Branding', 'label' => '🎨 Branding'],
                'es' => ['name' => 'Branding', 'label' => '🎨 Branding'],
                'fr' => ['name' => 'Branding', 'label' => '🎨 Branding'],
                'it' => ['name' => 'Branding', 'label' => '🎨 Branding'],
                'pt' => ['name' => 'Branding', 'label' => '🎨 Branding'],
                'tr' => ['name' => 'Marka kimliği', 'label' => '🎨 Marka kimliği'],
                'ru' => ['name' => 'Брендинг', 'label' => '🎨 Брендинг'],
            ]),
            'video' => $map([
                'de' => ['name' => 'Videoproduktion', 'label' => '🎬 Videoproduktion'],
                'es' => ['name' => 'Producción de video', 'label' => '🎬 Producción de video'],
                'fr' => ['name' => 'Production vidéo', 'label' => '🎬 Production vidéo'],
                'it' => ['name' => 'Produzione video', 'label' => '🎬 Produzione video'],
                'pt' => ['name' => 'Produção de vídeo', 'label' => '🎬 Produção de vídeo'],
                'tr' => ['name' => 'Video prodüksiyon', 'label' => '🎬 Video prodüksiyon'],
                'ru' => ['name' => 'Видеопроизводство', 'label' => '🎬 Видеопроизводство'],
            ]),
            'social' => $map([
                'de' => ['name' => 'Social Media', 'label' => '📱 Social Media'],
                'es' => ['name' => 'Redes sociales', 'label' => '📱 Redes sociales'],
                'fr' => ['name' => 'Réseaux sociaux', 'label' => '📱 Réseaux sociaux'],
                'it' => ['name' => 'Social media', 'label' => '📱 Social media'],
                'pt' => ['name' => 'Redes sociais', 'label' => '📱 Redes sociais'],
                'tr' => ['name' => 'Sosyal medya', 'label' => '📱 Sosyal medya'],
                'ru' => ['name' => 'Соцсети', 'label' => '📱 Соцсети'],
            ]),
        ],
        'blog' => [
            'digital-ads' => $map([
                'de' => ['name' => 'Digitale Anzeigen', 'label' => 'Digitale Anzeigen'],
                'es' => ['name' => 'Anuncios digitales', 'label' => 'Anuncios digitales'],
                'fr' => ['name' => 'Publicités digitales', 'label' => 'Publicités digitales'],
                'it' => ['name' => 'Annunci digitali', 'label' => 'Annunci digitali'],
                'pt' => ['name' => 'Anúncios digitais', 'label' => 'Anúncios digitais'],
                'tr' => ['name' => 'Dijital reklamlar', 'label' => 'Dijital reklamlar'],
                'ru' => ['name' => 'Цифровая реклама', 'label' => 'Цифровая реклама'],
            ]),
            'design' => $map([
                'de' => ['name' => 'Design', 'label' => 'Design'],
                'es' => ['name' => 'Diseño', 'label' => 'Diseño'],
                'fr' => ['name' => 'Design', 'label' => 'Design'],
                'it' => ['name' => 'Design', 'label' => 'Design'],
                'pt' => ['name' => 'Design', 'label' => 'Design'],
                'tr' => ['name' => 'Tasarım', 'label' => 'Tasarım'],
                'ru' => ['name' => 'Дизайн', 'label' => 'Дизайн'],
            ]),
            'content' => $map([
                'de' => ['name' => 'Content', 'label' => 'Content'],
                'es' => ['name' => 'Contenido', 'label' => 'Contenido'],
                'fr' => ['name' => 'Contenu', 'label' => 'Contenu'],
                'it' => ['name' => 'Contenuti', 'label' => 'Contenuti'],
                'pt' => ['name' => 'Conteúdo', 'label' => 'Conteúdo'],
                'tr' => ['name' => 'İçerik', 'label' => 'İçerik'],
                'ru' => ['name' => 'Контент', 'label' => 'Контент'],
            ]),
            'marketing-strategy' => $map([
                'de' => ['name' => 'Marketingstrategie', 'label' => 'Marketingstrategie'],
                'es' => ['name' => 'Estrategia de marketing', 'label' => 'Estrategia de marketing'],
                'fr' => ['name' => 'Stratégie marketing', 'label' => 'Stratégie marketing'],
                'it' => ['name' => 'Strategia di marketing', 'label' => 'Strategia di marketing'],
                'pt' => ['name' => 'Estratégia de marketing', 'label' => 'Estratégia de marketing'],
                'tr' => ['name' => 'Pazarlama stratejisi', 'label' => 'Pazarlama stratejisi'],
                'ru' => ['name' => 'Маркетинговая стратегия', 'label' => 'Маркетинговая стратегия'],
            ]),
        ],
    ],
];
