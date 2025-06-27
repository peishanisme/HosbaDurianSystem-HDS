require("@nomicfoundation/hardhat-toolbox");
require("dotenv").config();

const { API_URL, PRIVATE_KEY } = process.env;

/** @type import('hardhat/config').HardhatUserConfig */
module.exports = {
  solidity: "0.8.28",
  networks: {
    polygon: {
      url: API_URL,
      accounts: [`0x${PRIVATE_KEY}`]
    }
  }
};

// This is a sample Hardhat task. To learn how to create your own go to

// https://hardhat.org/guides/create-task.html

task("accounts", "Prints the list of accounts", async (taskArgs, hre) => {

  const accounts = await hre.ethers.getSigners();

  for (const account of accounts) {

    console.log(account.address);

  }

});



