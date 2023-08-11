const searchInput = document.getElementById('search')
const searchBtn = document.getElementById('search-btn')

searchBtn.addEventListener('click', ()=>{
    console.log(searchInput.value)
    search(searchInput.value)
})

async function search(search) {
    try {
        const response = await axios.get('/book?q='+search);
        console.log(response)
    } catch (error) {
        console.error(error);
    }
}