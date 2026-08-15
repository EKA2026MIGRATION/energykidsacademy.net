const galleryItems = document.querySelectorAll('.gallery-item');
const currentModal = document.querySelector('.mymodal');
const modalImage = document.getElementById('mymodal-image');
const prevButton = document.getElementById('prev-btn');
const nextButton = document.getElementById('next-btn');
const closeButton = document.getElementById('close-btn');
const modalDescription = document.querySelector('.mymodal-description');

let currentIndex = 0;

galleryItems.forEach(item => {
    item.addEventListener('click', () => {
        modalImage.src = item.querySelector('img').src;
        modalDescription.innerHTML = item.querySelector('.photo-details').innerHTML;

        currentModal.classList.add('show');
    });
});

closeButton.addEventListener('click', () => {
    currentModal.classList.remove('show');
});

prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
    modalImage.src = galleryItems[currentIndex].querySelector('img').src;
    modalDescription.innerHTML = galleryItems[currentIndex].querySelector('.photo-details').innerHTML;

});

nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % galleryItems.length;
    modalImage.src = galleryItems[currentIndex].querySelector('img').src;
    modalDescription.innerHTML = galleryItems[currentIndex].querySelector('.photo-details').innerHTML;

});