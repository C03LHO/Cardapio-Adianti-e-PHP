// Cardápio Moderno - JavaScript

// Aguardar o carregamento da página
document.addEventListener('DOMContentLoaded', function() {
    
    // Animação suave para os cards
    const cards = document.querySelectorAll('.produto-card');
    
    // Observer para animações ao scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Aplicar observer nos cards
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
    
    // Efeito hover nos botões
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Scroll suave para as seções
    const categoriaHeaders = document.querySelectorAll('.categoria-header');
    categoriaHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            this.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });
    
    // Adicionar loading aos botões de ação
    const actionButtons = document.querySelectorAll('.produto-actions button');
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Carregando...';
            this.disabled = true;
            
            // Simular delay e restaurar botão
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    });
    
    // Função para filtrar produtos por categoria
    window.filtrarCategoria = function(categoriaId) {
        const sections = document.querySelectorAll('.categoria-section');
        sections.forEach(section => {
            if (categoriaId === 'all' || section.dataset.categoriaId === categoriaId) {
                section.style.display = 'block';
                // Animar entrada
                section.style.opacity = '0';
                setTimeout(() => {
                    section.style.opacity = '1';
                }, 100);
            } else {
                section.style.display = 'none';
            }
        });
    };
    
    // Adicionar efeito de parallax ao header
    const header = document.querySelector('.cardapio-header');
    if (header) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // Contador animado para preços
    const precos = document.querySelectorAll('.produto-preco');
    precos.forEach(preco => {
        const valor = preco.textContent.replace('R$ ', '').replace(',', '.');
        const valorNumerico = parseFloat(valor);
        
        if (!isNaN(valorNumerico)) {
            preco.style.opacity = '0';
            
            // Animar quando entrar em vista
            observer.observe(preco);
            
            preco.addEventListener('animationend', function() {
                let start = 0;
                const duration = 1000;
                const startTime = performance.now();
                
                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    const currentValue = start + (valorNumerico - start) * progress;
                    preco.textContent = 'R$ ' + currentValue.toFixed(2).replace('.', ',');
                    
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    }
                }
                
                requestAnimationFrame(animate);
            }, { once: true });
        }
    });
    
    console.log('Cardápio Moderno carregado com sucesso!');
});

// Função para recarregar o cardápio
window.recarregarCardapio = function() {
    location.reload();
};

// Função para imprimir cardápio
window.imprimirCardapio = function() {
    // Esconder elementos de admin antes de imprimir
    const adminPanels = document.querySelectorAll('.admin-panel, .produto-actions');
    adminPanels.forEach(panel => {
        panel.style.display = 'none';
    });
    
    window.print();
    
    // Restaurar elementos após impressão
    setTimeout(() => {
        adminPanels.forEach(panel => {
            panel.style.display = '';
        });
    }, 1000);
};

// ==================================================
//   JavaScript para Cardápio Moderno
// ==================================================

$(document).ready(function() {
    
    // Animação suave ao rolar para categorias
    $('.categoria-header').click(function() {
        var categoria = $(this).closest('.categoria-section');
        var produtos = categoria.find('.produtos-grid');
        
        if (produtos.is(':visible')) {
            produtos.slideUp(300);
        } else {
            produtos.slideDown(300);
        }
    });
    
    // Efeito de carregamento nos botões
    $('.produto-actions .btn').click(function() {
        var btn = $(this);
        var originalText = btn.html();
        
        btn.html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
        btn.prop('disabled', true);
        
        // Restaurar o botão após 2 segundos (para demo)
        setTimeout(function() {
            btn.html(originalText);
            btn.prop('disabled', false);
        }, 2000);
    });
    
    // Efeito parallax no header
    $(window).scroll(function() {
        var scrolled = $(window).scrollTop();
        var header = $('.cardapio-header');
        var rate = scrolled * -0.5;
        
        header.css('transform', 'translateY(' + rate + 'px)');
    });
    
    // Contador animado de produtos
    function animateCounter() {
        $('.produto-preco').each(function() {
            var $this = $(this);
            var text = $this.text();
            var price = parseFloat(text.replace('R$ ', '').replace(',', '.'));
            
            if (!isNaN(price)) {
                $this.prop('Counter', 0).animate({
                    Counter: price
                }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function(now) {
                        $this.text('R$ ' + now.toFixed(2).replace('.', ','));
                    },
                    complete: function() {
                        $this.text('R$ ' + price.toFixed(2).replace('.', ','));
                    }
                });
            }
        });
    }
    
    // Executar animação do contador quando o elemento ficar visível
    $(window).scroll(function() {
        $('.produto-preco').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                if (!$(this).hasClass('animated')) {
                    $(this).addClass('animated');
                    // animateCounter(); // Descomente para ativar animação de preço
                }
            }
        });
    });
    
    // Filtro de busca em tempo real
    if ($('#search-produtos').length) {
        $('#search-produtos').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            
            $('.produto-card').filter(function() {
                var productName = $(this).find('.produto-nome').text().toLowerCase();
                var productDesc = $(this).find('.produto-descricao').text().toLowerCase();
                
                $(this).toggle(productName.indexOf(value) > -1 || productDesc.indexOf(value) > -1);
            });
            
            // Ocultar categorias vazias
            $('.categoria-section').each(function() {
                var visibleProducts = $(this).find('.produto-card:visible').length;
                $(this).toggle(visibleProducts > 0);
            });
        });
    }
    
    // Toast notifications para ações
    window.showToast = function(message, type = 'success') {
        var toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var iconClass = type === 'success' ? 'fa-check' : 'fa-exclamation-triangle';
        
        var toast = $(`
            <div class="alert ${toastClass} toast-notification" style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            ">
                <i class="fa ${iconClass}"></i> ${message}
                <button type="button" class="close" style="float: right; background: none; border: none; font-size: 1.2em;">
                    <span>&times;</span>
                </button>
            </div>
        `);
        
        $('body').append(toast);
        
        // Animar entrada
        setTimeout(function() {
            toast.css({
                opacity: 1,
                transform: 'translateX(0)'
            });
        }, 100);
        
        // Auto-remover após 5 segundos
        setTimeout(function() {
            toast.css({
                opacity: 0,
                transform: 'translateX(100%)'
            });
            
            setTimeout(function() {
                toast.remove();
            }, 300);
        }, 5000);
        
        // Remover ao clicar no X
        toast.find('.close').click(function() {
            toast.css({
                opacity: 0,
                transform: 'translateX(100%)'
            });
            
            setTimeout(function() {
                toast.remove();
            }, 300);
        });
    };
    
    // Lazy loading para imagens
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Efeito de digitação no título
    function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.innerHTML = '';
        
        function type() {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        type();
    }
    
    // Aplicar efeito de digitação no título (opcional)
    // var titulo = document.querySelector('.cardapio-header h1');
    // if (titulo) {
    //     var textoOriginal = titulo.textContent;
    //     typeWriter(titulo, textoOriginal, 150);
    // }
    
    console.log('Cardápio Moderno carregado com sucesso! 🍽️');
});

