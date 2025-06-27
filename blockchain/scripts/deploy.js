async function main() {
   const Lock = await ethers.getContractFactory("Lock");

   // Start deployment, returning a promise that resolves to a contract object
   const lock = await Lock.deploy(1751068800);   
   console.log("Contract deployed to address:", lock.address);
   console.log("Results", lock);
}

main()
  .then(() => process.exit(0))
  .catch(error => {
    console.error(error);
    process.exit(1);
  });