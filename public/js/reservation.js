document.addEventListener('DOMContentLoaded', function() {
    
    const monthNames = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];
    const dayNames = ["Neděle", "Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota"];

    let currentDate = new Date();
    let selectedDate = new Date();
    
    // Globální proměnné pro uložení aktuálních cen z vybrané aktivity
    let activePricePerHour = 0;
    let activePricePerDay = 0;

    const calMonthText = document.getElementById('calMonthText');
    const calDaysContainer = document.getElementById('calDaysContainer');
    const hiddenDateOutput = document.getElementById('selectedDateOutput');
    const slotsContainer = document.getElementById('dynamicSlotsContainer');
    const priceDisplay = document.getElementById('calculated-price');
    const calendarStep = document.getElementById('calendar-step');
    const slotsStep = document.getElementById('slots-step');
    const submitBtn = document.getElementById('submit-btn');

    // SCROLLOVÁNÍ ŠIPKAMI
    document.getElementById('scrollPrev').addEventListener('click', function() {
        calDaysContainer.scrollBy({ left: -240, behavior: 'smooth' });
    });

    document.getElementById('scrollNext').addEventListener('click', function() {
        calDaysContainer.scrollBy({ left: 240, behavior: 'smooth' });
    });

    function formatDateForInput(d) {
        return d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, '0') + "-" + String(d.getDate()).padStart(2, '0');
    }

    function renderSlots(dateStr, activityId) {
        slotsContainer.innerHTML = '<div class="text-center p-4 text-muted"><i class="fa-solid fa-spinner fa-spin"></i> Načítám volné termíny...</div>';
        
        fetch(`/api/reservation/availability?date=${dateStr}&activity_id=${activityId}`)
            .then(response => response.json())
            .then(data => {
                slotsContainer.innerHTML = '';
                
                if (data.is_blocked || data.slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="alert alert-warning text-center w-100 border-0 shadow-sm">Pro tento den nejsou vypsány žádné volné termíny.</div>';
                    updatePrice();
                    return;
                }

                data.slots.forEach(slotData => {
                    let html = "";
                    
                    // Vytvoření drobného textu se jmény dětí (např. "Tomík, Anička")
                    let namesHtml = "";
                    if (slotData.children_names && slotData.children_names.length > 0) {
                        let namesList = slotData.children_names.join(', ');
                        namesHtml = `<div class="mt-1" style="font-size: 0.7rem; color: #64748b; line-height: 1.2; font-weight: 500;">${namesList}</div>`;
                    }

                    if (slotData.status === 'FULL') {
                        html = `
                            <div class="col-6">
                                <label class="slot-checkbox-label w-100 disabled">
                                    <input type="checkbox" disabled>
                                    <div class="slot-box">
                                        ${slotData.slot}<br>
                                        <small class="fw-normal text-danger">Obsazeno</small>
                                        ${namesHtml}
                                    </div>
                                </label>
                            </div>`;
                    } else {
                        let subText = slotData.status === 'SHARED' ? `Skupina (${slotData.current_kids}/${slotData.max_capacity})` : 'Volno';
                        html = `
                            <div class="col-6">
                                <label class="slot-checkbox-label w-100">
                                    <input type="checkbox" name="slot[]" value="${slotData.slot}" class="slot-element">
                                    <div class="slot-box">
                                        ${slotData.slot}<br>
                                        <small class="fw-normal">${subText}</small>
                                        ${namesHtml}
                                    </div>
                                </label>
                            </div>`;
                    }
                    slotsContainer.insertAdjacentHTML('beforeend', html);
                });

                document.querySelectorAll('.slot-element').forEach(checkbox => {
                    checkbox.addEventListener('change', updatePrice);
                });
                updatePrice();
            });
    }

    // --- NOVÁ, CHYTŘEJŠÍ FUNKCE PRO VÝPOČET CENY ---
    function updatePrice() {
        const checkedCount = document.querySelectorAll('.slot-element:checked').length;
        const pricingSelect = document.querySelector('select[name="pricing"]');
        const pricingModel = pricingSelect ? pricingSelect.value : '';
        
        let totalPrice = 0;

        // Rozhodování na základě zvoleného modelu v roletce
        if (pricingModel === 'Celodenní parťák') {
            // Pokud je paušál, bereme denní cenu a nezajímá nás počet zakliknutých hodin
            totalPrice = activePricePerDay;
        } else {
            // Pokud je hodinová, násobíme počet vybraných hodin hodinovou sazbou
            totalPrice = checkedCount * activePricePerHour;
        }

        priceDisplay.innerText = totalPrice.toLocaleString('cs-CZ') + " Kč";
    }

    // Posluchač pro změnu cenového modelu (z hodinové na paušál a zpět)
    const pricingSelectNode = document.querySelector('select[name="pricing"]');
    if (pricingSelectNode) {
        pricingSelectNode.addEventListener('change', updatePrice);
    }

    function renderCalendar() {
        const activeRadio = document.querySelector('.activity-radio:checked');
        if (!activeRadio) return;

        const allowedDays = JSON.parse(activeRadio.getAttribute('data-days'));
        const themeColor = activeRadio.getAttribute('data-color');
        
        // Získání obou cen z HTML atributů zvolené aktivity
        activePricePerHour = parseFloat(activeRadio.getAttribute('data-price')) || 0;
        activePricePerDay = parseFloat(activeRadio.getAttribute('data-price-day')) || 0;

        calendarStep.style.setProperty('--theme-color', themeColor);
        slotsStep.style.setProperty('--theme-color', themeColor);
        submitBtn.style.background = themeColor;

        calDaysContainer.innerHTML = '';
        let firstAvailableDateSet = false;

        // Generujeme 60 dní (cca 2 měsíce)
        for (let i = 0; i < 60; i++) {
            let d = new Date(currentDate);
            d.setDate(d.getDate() + i);

            let dayOfWeek = d.getDay();
            let isDayAllowed = allowedDays.includes(dayOfWeek);
            let isSelected = selectedDate.toDateString() === d.toDateString();
            let isToday = new Date().toDateString() === d.toDateString();
            let dayLabel = isToday && i === 0 ? "Dnes" : dayNames[dayOfWeek];

            let btn = document.createElement('div');
            
            if (isDayAllowed) {
                btn.className = 'day-btn available' + (isSelected ? ' active' : '');
                btn.innerHTML = `<span class="d-block small text-muted">${dayLabel}</span><strong class="d-block fs-5">${d.getDate()}. ${d.getMonth()+1}.</strong>`;
                
                if (!firstAvailableDateSet && (!isSelected || hiddenDateOutput.value === '')) {
                    selectedDate = new Date(d);
                    isSelected = true;
                    btn.classList.add('active');
                    firstAvailableDateSet = true;
                }

                btn.onclick = function() {
                    // OPRAVA CHYBY: Hledáme dny uvnitř #calDaysContainer
                    document.querySelectorAll('#calDaysContainer .day-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    selectedDate = new Date(d);
                    hiddenDateOutput.value = formatDateForInput(selectedDate);
                    calMonthText.innerHTML = `<i class="fa-regular fa-calendar me-2"></i> ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
                    
                    renderSlots(hiddenDateOutput.value, activeRadio.value);
                };
            } else {
                btn.className = 'day-btn disabled';
                btn.innerHTML = `<span class="d-block small text-muted">${dayLabel}</span><strong class="d-block text-muted">${d.getDate()}. ${d.getMonth()+1}.</strong>`;
            }
            calDaysContainer.appendChild(btn);
        }

        calMonthText.innerHTML = `<i class="fa-regular fa-calendar me-2"></i> ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
        hiddenDateOutput.value = formatDateForInput(selectedDate);
        renderSlots(hiddenDateOutput.value, activeRadio.value);
        
        // Zavoláme přepočet ceny rovnou při vykreslení kalendáře
        updatePrice();
    }

    document.querySelectorAll('.activity-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.activity-card').forEach(card => card.classList.remove('active'));
            this.parentNode.querySelector('.activity-card').classList.add('active');
            
            currentDate = new Date();
            hiddenDateOutput.value = '';
            renderCalendar();
            calDaysContainer.scrollLeft = 0; // Při změně aktivity vrátíme posuvník na začátek
        });
    });

    renderCalendar();
});