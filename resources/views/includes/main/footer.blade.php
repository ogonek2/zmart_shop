<!-- Футер -->
<footer class="footer bg-dark text-white py-5 mt-5">
    <div class="container">
        <!-- Основная информация -->
        <div class="row g-4 mb-5">
            <!-- О компании -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-4">
                    <h3 class="text-gradient mb-3">
                        <i class="fas fa-bolt text-warning me-2"></i>ZMART
                    </h3>
                    <p class="text-light mb-3">
                        Ваш надежный партнер в мире бытовой техники. Мы предлагаем качественные товары 
                        от ведущих мировых брендов по доступным ценам.
                    </p>
                </div>
            </div>

            <!-- Каталог -->
            <!--<div class="col-lg-2 col-md-6">-->
            <!--    <h5 class="footer-title mb-3">Каталог</h5>-->
            <!--    <ul class="footer-links">-->
            <!--        <li><a href="#">Все товары</a></li>-->
            <!--        <li><a href="#">Новинки</a></li>-->
            <!--        <li><a href="#">Акции</a></li>-->
            <!--        <li><a href="#">Популярные</a></li>-->
            <!--        <li><a href="#">Бренды</a></li>-->
            <!--    </ul>-->
            <!--</div>-->

            <!-- Информация -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title mb-3">Информация</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('pro_kompaniiu') }}">О компании</a></li>
                    <li><a href="{{ route('kontaktna_informatsiia') }}">Контакты</a></li>
                    <li><a href="{{ route('dohovir_oferty') }}">Договор оферты</a></li>
                    <li><a href="{{ route('uhoda_korystuvacha') }}">Условия использования</a></li>
                    <li><a href="{{ route('privacy_policy') }}">Политика конфиденциальности</a></li>
                     <li><a href="{{ route('oplata_i_dostavka') }}">Оплата и доставка</a></li>
                    <li><a href="{{ route('obmin_ta_povernennia') }}">Обмен и возврат</a></li>
                </ul>
            </div>

            <!-- Контакты -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title mb-3">Контакты</h5>
                <div class="contact-info">
                    <div class="contact-item mb-3">
                        <i class="fas fa-phone text-warning me-2"></i>
                        <a href="tel:+380730777572" class="text-light text-decoration-none">
                            +38 073-077-75-72
                        </a>
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-envelope text-warning me-2"></i>
                        <a href="mailto:zmartcomua@gmail.com" class="text-light text-decoration-none">
                            zmartcomua@gmail.com
                        </a>
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-map-marker-alt text-warning me-2"></i>
                        <span class="text-light">
                            Одесса, пром рынок "7 км",<br>
                            ул.Фабричная, маг. №2178
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительные секции -->
        <div class="row g-4 mb-5">
            <!-- Преимущества -->
            <div class="col-12">
                <div class="features-section">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-item text-center">
                                <div class="feature-icon mb-2">
                                    <i class="fas fa-shipping-fast fa-2x text-warning"></i>
                                </div>
                                <h6 class="text-light mb-1">Быстрая доставка</h6>
                                <small class="text-muted">По всей Украине</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-item text-center">
                                <div class="feature-icon mb-2">
                                    <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                </div>
                                <h6 class="text-light mb-1">Гарантия качества</h6>
                                <small class="text-muted">Официальная гарантия</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-item text-center">
                                <div class="feature-icon mb-2">
                                    <i class="fas fa-headset fa-2x text-warning"></i>
                                </div>
                                <h6 class="text-light mb-1">Поддержка 24/7</h6>
                                <small class="text-muted">Всегда на связи</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-item text-center">
                                <div class="feature-icon mb-2">
                                    <i class="fas fa-credit-card fa-2x text-warning"></i>
                                </div>
                                <h6 class="text-light mb-1">Удобная оплата</h6>
                                <small class="text-muted">Множество способов</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Разделитель -->
        <hr class="border-secondary my-4">

    </div>
</footer>

<style>
.footer {
    background: linear-gradient(135deg, #1e293b, #334155);
    position: relative;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--secondary-color), transparent);
}

.footer-brand h3 {
    font-size: 1.8rem;
    font-weight: 700;
}

.footer-title {
    color: var(--secondary-color);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 30px;
    height: 2px;
    background: var(--secondary-color);
    border-radius: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.footer-links a:hover {
    color: var(--secondary-color);
    transform: translateX(5px);
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--secondary-color);
    color: white;
    transform: translateY(-3px);
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.contact-item i {
    margin-top: 0.25rem;
}

.features-section {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.feature-item {
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-2px);
}

.newsletter-section {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.newsletter-form .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 8px 0 0 8px;
}

.newsletter-form .form-control::placeholder {
    color: #cbd5e1;
}

.newsletter-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    color: white;
}

.newsletter-form .btn {
    border-radius: 0 8px 8px 0;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

.payment-methods i {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.payment-methods i:hover {
    opacity: 1;
}

/* Адаптивность */
@media (max-width: 768px) {
    .footer-brand {
        text-align: center;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .features-section {
        padding: 1rem;
    }
    
    .newsletter-section {
        padding: 1.5rem;
    }
    
    .newsletter-form .input-group {
        flex-direction: column;
    }
    
    .newsletter-form .form-control,
    .newsletter-form .btn {
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .payment-methods {
        text-align: center;
        margin-top: 1rem;
    }
}

/* CSS переменные для совместимости */
:root {
    --primary-color: #2563eb;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --dark-color: #1e293b;
    --border-color: #e2e8f0;
}
</style>
