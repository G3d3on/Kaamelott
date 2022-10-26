// function Card()
// {

//     const [cards] = React.useState()

//     const API = 'http://localhost:8000/carte'

   
//         fetch(API, {
//             method: 'GET',
//             headers : {
//                 'Accept': 'application/json',
//                 'Content-Type': 'application/x-www-form-urlencoded',
//                 'X-Requested-With' : 'XMLHTTPRequest',
//             },
//             mode: 'no-cors',
//             cache: 'default'
//         })

//         .then(response => response.text())
//         .then(data => {
//             cards = data.results
//             console.log(cards)
//             console.log("api call")
//         })

//     return(
//         <div className="carte">
//             { cards.map( (card) => {
//                     <div className="carte-contour">
//                         <div className="carte-cadre-nom">
//                             <p className="carte-nom">{ card.nom }</p>
//                         </div>
//                         <div className="carte-cadre-personnage">
//                             <img className="carte-cadre-personnage-image" src="/images/cartes/personnages/" alt=""></img>
//                             <span className="carte-personnage-attaque"></span>
//                             <span className="carte-personnage-defense"></span>
//                         </div>
//                         <div className="carte-cadre-description">
//                             <h5 className="carte-classNamee"></h5>
//                             <p className="carte-description"></p>
//                         </div>
//                     </div>
//                 })
//             }
//         </div>
//     )
// }

// ReactDOM.render(
//     <Card />, 
//     document.querySelector('#terrain-cadrillage-duel-contour')
// );