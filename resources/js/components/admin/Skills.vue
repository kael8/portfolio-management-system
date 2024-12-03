<script setup>
import { reactive, onMounted, ref, computed } from "vue";
import { PlusIcon, TrashIcon, CheckIcon } from "@heroicons/vue/24/outline";
import apiClient from "@/services/apiClient";
import { useToast } from "vue-toastification";
import { v4 as uuidv4 } from "uuid"; // Import UUID library for unique IDs

const state = reactive({
    skills: [{ id: uuidv4(), name: "", type: "" }],
});

const toast = useToast();

const names = reactive([
    // Frontend Frameworks and Libraries
    "React",
    "Vue.js",
    "Angular",
    "Svelte",
    "Next.js",
    "Nuxt.js",
    "Gatsby",
    "Remix",
    "Solid.js",
    "Alpine.js",
    "Stimulus",
    "Lit",
    "Ember.js",
    "Backbone.js",
    "Preact",
    "Electron",
    "Stencil",
    "Meteor",
    "Aurelia",
    "Mithril",
    "JavaScript",
    "JQuery",

    // Backend Frameworks
    "Node.js",
    "Express",
    "Nest.js",
    "Fastify",
    "Koa",
    "Sails.js",
    "Adonis.js",
    "Strapi",
    "Feathers",
    "Hapi",
    "LoopBack",
    "Moleculer",
    "Total.js",
    "Micro",
    "Restify",
    "Serverless",
    "Egg.js",
    "Midway",
    "RedwoodJS",
    "Blitz.js",

    // Mobile Development
    "React Native",
    "Flutter",
    "Ionic",
    "Xamarin",
    "NativeScript",
    "PhoneGap",
    "Cordova",
    "Dart",
    "Swift",
    "Kotlin Multiplatform",
    "Titanium",
    "Flutter",
    "AppGyver",
    "Quasar",
    "Framework7",
    "Onsen UI",
    "JQuery Mobile",
    "Sencha Touch",

    // Backend Languages and Runtimes
    "Python",
    "Django",
    "Flask",
    "FastAPI",
    "Ruby",
    "Ruby on Rails",
    "Sinatra",
    "PHP",
    "Laravel",
    "Symfony",
    "CodeIgniter",
    "Golang",
    "Rust",
    "Scala",
    "Java",
    "Spring",
    "Kotlin",
    "Ktor",
    "Micronaut",
    "Quarkus",
    "CakePHP",
    "Yii",
    "Zend",
    "Elixir",
    "Phoenix",
    "Crystal",
    "Nim",
    "Dart",
    "Julia",

    // Databases
    "MySQL",
    "PostgreSQL",
    "MongoDB",
    "SQLite",
    "Redis",
    "Cassandra",
    "CouchDB",
    "MariaDB",
    "Oracle",
    "Microsoft SQL Server",
    "Neo4j",
    "Elasticsearch",
    "DynamoDB",
    "RethinkDB",
    "ArangoDB",
    "Couchbase",
    "Firebase Realtime Database",
    "Firestore",
    "TimeScaleDB",
    "InfluxDB",
    "Dgraph",
    "OrientDB",
    "Cockroach DB",

    // Cloud Platforms
    "AWS",
    "Azure",
    "Google Cloud",
    "IBM Cloud",
    "Oracle Cloud",
    "Alibaba Cloud",
    "DigitalOcean",
    "Heroku",
    "Vultr",
    "Linode",
    "OpenStack",
    "Render",
    "Railway",
    "Vercel",
    "Netlify",
    "CloudFlare",
    "Firebase",
    "FoundationDB",
    "OpenShift",
    "AppEngine",

    // DevOps and Infrastructure
    "Docker",
    "Kubernetes",
    "Jenkins",
    "Travis CI",
    "CircleCI",
    "GitHub Actions",
    "GitLab CI",
    "Ansible",
    "Terraform",
    "Puppet",
    "Chef",
    "Nagios",
    "Prometheus",
    "Grafana",
    "ELK Stack",
    "Datadog",
    "New Relic",
    "Splunk",
    "ArgoCD",
    "Istio",
    "Helm",
    "Spinnaker",
    "Rancher",
    "Crossplane",
    "SaltStack",
    "Packer",

    // Tools
    "Git",
    "GitHub",
    "GitLab",
    "Bitbucket",
    "VS Code",
    "Sublime Text",
    "Atom",
    "WebStorm",
    "Postman",
    "Insomnia",
    "Stripe",
    "Twilio",
    "SendGrid",
    "Figma",
    "Sketch",
    "Adobe XD",
    "InVision",
    "Zeplin",
    "Framer",
    "Blender",
    "Unity",
    "Unreal Engine",
    "Three.js",
    "WebGL",
    "TensorFlow",
    "PyTorch",
    "Keras",
    "Scikit-learn",
    "Pandas",
    "NumPy",
    "Matplotlib",
    "Seaborn",
    "D3.js",
    "Chart.js",
    "Highcharts",
    "Plotly",
    "Tableau",
    "Power BI",
    "Qlik",
    "Looker",
    "Metabase",
    "Superset",
    "Airflow",
    "Luigi",
    "Prefect",
    "Dagster",
    "MLflow",
    "Kubeflow",
    "Tecton",
    "Feast",
    "Great Expectations",
    "DBT",
    "Snowflake",
    "BigQuery",
    "Redshift",
    "Athena",
    "Presto",
    "Hive",
    "Hadoop",
    "Spark",
    "Flink",
    "Storm",
    "Samza",
    "Beam",
    "NiFi",
    "Kafka Streams",
    "KSQL",
    "Kinesis",
    "Firehose",
    "Glue",
    "Data Pipeline",
    "Step Functions",
    "Lambda",
    "API Gateway",
    "AppSync",
    "Cognito",
    "IAM",
    "CloudFormation",
    "CDK",
    "Amplify",
    "S3",
    "DynamoDB",
    "RDS",
    "Aurora",
    "Neptune",
    "DocumentDB",
    "ElastiCache",
    "Elastic Beanstalk",
    "Lightsail",
    "Fargate",
    "ECS",
    "EKS",
    "Batch",
    "CodeBuild",
    "CodePipeline",
    "CodeDeploy",
    "CodeCommit",
    "CodeStar",
    "Cloud9",
    "X-Ray",
    "CloudWatch",
    "CloudTrail",
    "Config",
    "GuardDuty",
    "Inspector",
    "Macie",
    "Shield",
    "WAF",
    "Firewall Manager",
    "Security Hub",
    "Artifact",
    "KMS",
    "Secrets Manager",
    "Certificate Manager",
    "CloudHSM",
    "Directory Service",
    "Single Sign-On",
    "Organizations",
    "Control Tower",
    "Service Catalog",
    "Systems Manager",
    "Trusted Advisor",
    "Well-Architected Tool",
    "Personal Health Dashboard",
    "Service Health Dashboard",
    "Support Center",
    "Marketplace",
    "Cost Explorer",
    "Budgets",
    "Savings Plans",
    "Reserved Instances",
    "Spot Instances",
    "Savings Plans",
    "Cost and Usage Reports",
    "Billing and Cost Management",
    "AWS Pricing Calculator",
    "AWS Free Tier",
    "AWS Training and Certification",
    "AWS Documentation",
    "AWS Whitepapers",
    "AWS Solutions Library",
    "AWS Architecture Center",
    "AWS Partner Network",
    "AWS Marketplace",
    "AWS Service Catalog",
    "AWS Managed Services",
    "AWS Professional Services",
    "AWS Support",
    "AWS IQ",
    "AWS Well-Architected",
    "AWS Cloud Adoption Framework",
    "AWS Migration Hub",
    "AWS Application Discovery Service",
    "AWS Database Migration Service",
    "AWS Server Migration Service",
    "AWS DataSync",
    "AWS Transfer Family",
    "AWS Snow Family",
    "AWS Storage Gateway",
    "AWS Backup",
    "AWS Elastic Disaster Recovery",
    "AWS Outposts",
    "AWS Local Zones",
    "AWS Wavelength",
    "AWS Ground Station",
    "AWS Snowcone",
    "AWS Snowball",
    "AWS Snowmobile",
]);

const nameToType = {
    // Frontend Frameworks
    React: "Frontend",
    "Vue.js": "Frontend",
    Angular: "Frontend",
    Svelte: "Frontend",
    "Next.js": "Frontend",
    "Nuxt.js": "Frontend",
    Gatsby: "Frontend",
    Remix: "Frontend",
    "Solid.js": "Frontend",
    "Alpine.js": "Frontend",
    Stimulus: "Frontend",
    Lit: "Frontend",
    "Ember.js": "Frontend",
    "Backbone.js": "Frontend",
    Preact: "Frontend",
    Electron: "Frontend",
    Stencil: "Frontend",
    Meteor: "Frontend",
    Aurelia: "Frontend",
    Mithril: "Frontend",
    JavaScript: "Frontend",
    JQuery: "Frontend",

    // Backend Frameworks
    "Node.js": "Backend",
    Express: "Backend",
    "Nest.js": "Backend",
    Fastify: "Backend",
    Koa: "Backend",
    "Sails.js": "Backend",
    "Adonis.js": "Backend",
    Strapi: "Backend",
    Feathers: "Backend",
    Hapi: "Backend",
    LoopBack: "Backend",
    Moleculer: "Backend",
    "Total.js": "Backend",
    Micro: "Backend",
    Restify: "Backend",
    Serverless: "Backend",
    "Egg.js": "Backend",
    Midway: "Backend",
    RedwoodJS: "Backend",
    "Blitz.js": "Backend",

    // Mobile Development
    "React Native": "Mobile",
    Flutter: "Mobile",
    Ionic: "Mobile",
    Xamarin: "Mobile",
    NativeScript: "Mobile",
    PhoneGap: "Mobile",
    Cordova: "Mobile",
    Dart: "Mobile",
    Swift: "Mobile",
    "Kotlin Multiplatform": "Mobile",
    Titanium: "Mobile",
    AppGyver: "Mobile",
    Quasar: "Mobile",
    Framework7: "Mobile",
    "Onsen UI": "Mobile",
    "JQuery Mobile": "Mobile",
    "Sencha Touch": "Mobile",

    // Backend Languages and Frameworks
    Python: "Backend",
    Django: "Backend",
    Flask: "Backend",
    FastAPI: "Backend",
    Ruby: "Backend",
    "Ruby on Rails": "Backend",
    Sinatra: "Backend",
    PHP: "Backend",
    Laravel: "Backend",
    Symfony: "Backend",
    CodeIgniter: "Backend",
    Golang: "Backend",
    Rust: "Backend",
    Scala: "Backend",
    Java: "Backend",
    Spring: "Backend",
    Kotlin: "Backend",
    Ktor: "Backend",
    Micronaut: "Backend",
    Quarkus: "Backend",
    CakePHP: "Backend",
    Yii: "Backend",
    Zend: "Backend",
    Elixir: "Backend",
    Phoenix: "Backend",
    Crystal: "Backend",
    Nim: "Backend",
    Julia: "Backend",

    // Databases
    MySQL: "Database",
    PostgreSQL: "Database",
    MongoDB: "Database",
    SQLite: "Database",
    Redis: "Database",
    Cassandra: "Database",
    CouchDB: "Database",
    MariaDB: "Database",
    Oracle: "Database",
    "Microsoft SQL Server": "Database",
    Neo4j: "Database",
    Elasticsearch: "Database",
    DynamoDB: "Database",
    RethinkDB: "Database",
    ArangoDB: "Database",
    Couchbase: "Database",
    "Firebase Realtime Database": "Database",
    Firestore: "Database",
    TimeScaleDB: "Database",
    InfluxDB: "Database",
    Dgraph: "Database",
    OrientDB: "Database",
    "Cockroach DB": "Database",

    // Cloud Platforms
    AWS: "Cloud",
    Azure: "Cloud",
    "Google Cloud": "Cloud",
    "IBM Cloud": "Cloud",
    "Oracle Cloud": "Cloud",
    "Alibaba Cloud": "Cloud",
    DigitalOcean: "Cloud",
    Heroku: "Cloud",
    Vultr: "Cloud",
    Linode: "Cloud",
    OpenStack: "Cloud",
    Render: "Cloud",
    Railway: "Cloud",
    Vercel: "Cloud",
    Netlify: "Cloud",
    CloudFlare: "Cloud",
    Firebase: "Cloud",
    FoundationDB: "Cloud",
    OpenShift: "Cloud",
    AppEngine: "Cloud",

    // DevOps and Infrastructure
    Docker: "DevOps",
    Kubernetes: "DevOps",
    Jenkins: "DevOps",
    "Travis CI": "DevOps",
    CircleCI: "DevOps",
    "GitHub Actions": "DevOps",
    "GitLab CI": "DevOps",
    Ansible: "DevOps",
    Terraform: "DevOps",
    Puppet: "DevOps",
    Chef: "DevOps",
    Nagios: "DevOps",
    Prometheus: "DevOps",
    Grafana: "DevOps",
    "ELK Stack": "DevOps",
    Datadog: "DevOps",
    "New Relic": "DevOps",
    Splunk: "DevOps",
    ArgoCD: "DevOps",
    Istio: "DevOps",
    Helm: "DevOps",
    Spinnaker: "DevOps",
    Rancher: "DevOps",
    Crossplane: "DevOps",
    SaltStack: "DevOps",
    Packer: "DevOps",

    // Tools
    Git: "Tools",
    GitHub: "Tools",
    GitLab: "Tools",
    Bitbucket: "Tools",
    "VS Code": "Tools",
    "Sublime Text": "Tools",
    Atom: "Tools",
    WebStorm: "Tools",
    Postman: "Tools",
    Insomnia: "Tools",
    Stripe: "Tools",
    Twilio: "Tools",
    SendGrid: "Tools",
    Figma: "Tools",
    Sketch: "Tools",
    "Adobe XD": "Tools",
    InVision: "Tools",
    Zeplin: "Tools",
    Framer: "Tools",
    Blender: "Tools",
    Unity: "Tools",
    "Unreal Engine": "Tools",
    "Three.js": "Tools",
    WebGL: "Tools",
    TensorFlow: "Tools",
    PyTorch: "Tools",
    Keras: "Tools",
    "Scikit-learn": "Tools",
    Pandas: "Tools",
    NumPy: "Tools",
    Matplotlib: "Tools",
    Seaborn: "Tools",
    "D3.js": "Tools",
    "Chart.js": "Tools",
    Highcharts: "Tools",
    Plotly: "Tools",
    Tableau: "Tools",
    "Power BI": "Tools",
    Qlik: "Tools",
    Looker: "Tools",
    Metabase: "Tools",
    Superset: "Tools",
    Airflow: "Tools",
    Luigi: "Tools",
    Prefect: "Tools",
    Dagster: "Tools",
    MLflow: "Tools",
    Kubeflow: "Tools",
    Tecton: "Tools",
    Feast: "Tools",
    "Great Expectations": "Tools",
    DBT: "Tools",
    Snowflake: "Tools",
    BigQuery: "Tools",
    Redshift: "Tools",
    Athena: "Tools",
    Presto: "Tools",
    Hive: "Tools",
    Hadoop: "Tools",
    Spark: "Tools",
    Flink: "Tools",
    Storm: "Tools",
    Samza: "Tools",
    Beam: "Tools",
    NiFi: "Tools",
    "Kafka Streams": "Tools",
    KSQL: "Tools",
    Kinesis: "Tools",
    Firehose: "Tools",
    Glue: "Tools",
    "Data Pipeline": "Tools",
    "Step Functions": "Tools",
    Lambda: "Tools",
    "API Gateway": "Tools",
    AppSync: "Tools",
    Cognito: "Tools",
    IAM: "Tools",
    CloudFormation: "Tools",
    CDK: "Tools",
    Amplify: "Tools",
    S3: "Tools",
    DynamoDB: "Tools",
    RDS: "Tools",
    Aurora: "Tools",
    Neptune: "Tools",
    DocumentDB: "Tools",
    ElastiCache: "Tools",
    "Elastic Beanstalk": "Tools",
    Lightsail: "Tools",
    Fargate: "Tools",
    ECS: "Tools",
    EKS: "Tools",
    Batch: "Tools",
    CodeBuild: "Tools",
    CodePipeline: "Tools",
    CodeDeploy: "Tools",
    CodeCommit: "Tools",
    CodeStar: "Tools",
    Cloud9: "Tools",
    "X-Ray": "Tools",
    CloudWatch: "Tools",
    CloudTrail: "Tools",
    Config: "Tools",
    GuardDuty: "Tools",
    Inspector: "Tools",
    Macie: "Tools",
    Shield: "Tools",
    WAF: "Tools",
    "Firewall Manager": "Tools",
    "Security Hub": "Tools",
    Artifact: "Tools",
    KMS: "Tools",
    "Secrets Manager": "Tools",
    "Certificate Manager": "Tools",
    CloudHSM: "Tools",
    "Directory Service": "Tools",
    "Single Sign-On": "Tools",
    Organizations: "Tools",
    "Control Tower": "Tools",
    "Service Catalog": "Tools",
    "Systems Manager": "Tools",
    "Trusted Advisor": "Tools",
    "Well-Architected Tool": "Tools",
    "Personal Health Dashboard": "Tools",
    "Service Health Dashboard": "Tools",
    "Support Center": "Tools",
    Marketplace: "Tools",
    "Cost Explorer": "Tools",
    Budgets: "Tools",
    "Savings Plans": "Tools",
    "Reserved Instances": "Tools",
    "Spot Instances": "Tools",
    "Savings Plans": "Tools",
    "Cost and Usage Reports": "Tools",
    "Billing and Cost Management": "Tools",
    "AWS Pricing Calculator": "Tools",
    "AWS Free Tier": "Tools",
    "AWS Training and Certification": "Tools",
    "AWS Documentation": "Tools",
    "AWS Whitepapers": "Tools",
    "AWS Solutions Library": "Tools",
    "AWS Architecture Center": "Tools",
    "AWS Partner Network": "Tools",
    "AWS Marketplace": "Tools",
    "AWS Service Catalog": "Tools",
    "AWS Managed Services": "Tools",
    "AWS Professional Services": "Tools",
    "AWS Support": "Tools",
    "AWS IQ": "Tools",
    "AWS Well-Architected": "Tools",
    "AWS Cloud Adoption Framework": "Tools",
    "AWS Migration Hub": "Tools",
    "AWS Application Discovery Service": "Tools",
    "AWS Database Migration Service": "Tools",
    "AWS Server Migration Service": "Tools",
    "AWS DataSync": "Tools",
    "AWS Transfer Family": "Tools",
    "AWS Snow Family": "Tools",
    "AWS Storage Gateway": "Tools",
    "AWS Backup": "Tools",
    "AWS Elastic Disaster Recovery": "Tools",
    "AWS Outposts": "Tools",
    "AWS Local Zones": "Tools",
    "AWS Wavelength": "Tools",
    "AWS Ground Station": "Tools",
    "AWS Snowcone": "Tools",
    "AWS Snowball": "Tools",
    "AWS Snowmobile": "Tools",
};

const availableSkills = computed(() => {
    const selectedSkills = state.skills.map((skill) => skill.name);
    return names.value.filter((skill) => !selectedSkills.includes(skill));
});

onMounted(async () => {
    const fetchSkills = async () => {
        try {
            const response = await apiClient.get("/skills");

            state.skills = response.data.skills.map((skill) => ({
                id: skill.id,
                name: skill.name,
                type: skill.type,
                filteredNames: [],
            }));
        } catch (error) {
            console.error("Error fetching skills:", error);
        }
    };

    fetchSkills();
});

const addSkill = () => {
    state.skills.push({
        id: uuidv4(),
        name: "",
        type: "",
    });
};

const removeSkill = (id) => {
    state.skills = state.skills.filter((skill) => skill.id !== id);
};

const submitSkills = async () => {
    const validSkills = state.skills.filter(
        (skill) => skill.name.trim() !== "" && skill.type.trim() !== ""
    );
    if (validSkills.length === 0) {
        alert("Please add at least one valid skill.");
        return;
    }
    console.log("Submitted skills:", validSkills);
    try {
        const response = await apiClient.post("/skills", validSkills);
        console.log(response.data);
        toast.success("Skills submitted successfully");
        const fetchSkills = async () => {
            try {
                const response = await apiClient.get("/skills");

                state.skills = response.data.skills.map((skill) => ({
                    id: skill.id,
                    name: skill.name,
                    type: skill.type,
                    filteredNames: [],
                }));
            } catch (error) {
                console.error("Error fetching skills:", error);
            }
        };

        fetchSkills();
    } catch (error) {
        console.error("Error submitting skills:", error);
        toast.error("Failed to submit skills");
    }
};

const filteredNames = (input) => {
    return names.filter((name) =>
        name.toLowerCase().includes(input.toLowerCase())
    );
};
</script>

<template>
    <!--Manage Skills-->
    <div
        class="flex flex-col w-full h-full items-center rounded-md bg-gray-800 text-white justify-center p-6 mb-8"
    >
        <h1 class="text-4xl font-bold mb-6">Skills</h1>

        <div class="w-full max-w-md">
            <div
                v-for="skill in state.skills"
                :key="skill.id"
                class="relative flex items-center mb-2 space-x-2"
            >
                <input
                    v-model="skill.name"
                    type="text"
                    placeholder="Skill name"
                    class="flex-1 p-3 border border-gray-700 rounded-md bg-gray-900 text-white"
                    @input="skill.filteredNames = filteredNames(skill.name)"
                />
                <div
                    v-if="skill.filteredNames && skill.filteredNames.length"
                    class="absolute top-full left-0 right-0 bg-gray-700 rounded-md z-10"
                >
                    <div
                        v-for="(name, index) in skill.filteredNames"
                        :key="index"
                        class="p-2 hover:bg-gray-600 cursor-pointer"
                        @click="
                            skill.name = name;
                            skill.type = nameToType[name];
                            skill.filteredNames = [];
                        "
                    >
                        {{ name }}
                    </div>
                </div>
                <button
                    @click="removeSkill(skill.id)"
                    class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-md"
                >
                    <TrashIcon class="h-5 w-5" />
                </button>
            </div>
            <button
                @click="addSkill"
                class="w-full p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md mb-4 flex items-center justify-center space-x-2"
            >
                <PlusIcon class="h-5 w-5" />
                <span>Add Skill</span>
            </button>
            <button
                @click="submitSkills"
                class="w-full p-3 bg-green-600 hover:bg-green-700 text-white rounded-md flex items-center justify-center space-x-2"
            >
                <CheckIcon class="h-5 w-5" />
                <span>Submit</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Optional custom styling */
</style>
